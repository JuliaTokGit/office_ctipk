<?php

use Spacewind\FileUploader;

$result = [];

foreach ($page->objects as $key => $object) {
    if (isset($filters[$key])) {
        if (!isset($object->denied) || !in_array($user->type->str_id, $object->denied)) {
            $class = new $object->name();
        }
    }
}

function create($input, $class)
{
    $record = $class->create($input);
    if (isset($class->crosses)) {
        foreach ($class->crosses as $cross) {
            if (!isset($input[$cross])) {
                $input[$cross] = [];
            }
            if (is_array($input[$cross])) {
                $record->$cross()->detach();
                $record->touch();
                $sequence = 0;
                foreach ($input[$cross] as $posted) {
                    $record->$cross()->attach([$posted]);
                }
            }
         }
    }

    if (isset($class->linkages)) {
        foreach ($class->linkages as $linkag) {
            if (isset($input[$linkag])) {
                foreach ($input[$linkag] as $posted) {
                    $record->$linkag()->attach([$posted]);
                    $record->touch();
                }
            }
        }
    }

    if (isset($class->relations)) {
        foreach ($class->relations as $relation) {
            if (!isset($input[$relation])) {
                $input[$relation] = [];
            }
            if (!isset($class->crosses) || !in_array($relation, $class->crosses)) {
                $sequence = 0;
                if (array_key_exists(0, $input[$relation])) {
                    foreach ($input[$relation] as $posted) {
                        $record->$relation()->create($posted);
                    }
                } elseif (sizeof($input[$relation])) {
                    $record->$relation()->create($input[$relation]);
                }
            }
        }
        return $class->with($class->relations)->find($record->id);
    } else {
        return $class->find($record->id);
    }
}


function upload($file)
{
    global $path,$user;
    $uploaded = new FileUploader($file, $path['upload']);
    if ($uploaded->save()) {
        $upload = new Upload();
        $upload->description=$uploaded->name;
        $upload->filename = $uploaded->filename;
        $upload->full_path = $uploaded->full_path;
        $upload->file_size = $uploaded->size;
        $upload->content_type = $uploaded->type;
        $upload->user_id = $user->id;
        $upload->save();
        return $upload->id;
    }
}


if (isset($class)) {
    if (!empty($_FILES)) {
      // print_r($_FILES);
      // die();
        foreach ($_FILES as $field => $file) {
            if (!empty($file['name'])) {
                if ($_POST['action']=='create' && is_array($file['name'])) {
                    $multiple_upload=true;
                    $multiple_items=[];
                    $total = count($file['name']);
                    for ($i=0 ; $i < $total ; $i++) {
                      $single_file=[
                        'name'=>$file['name'][$i],
                        'type'=>$file['type'][$i],
                        'tmp_name'=>$file['tmp_name'][$i],
                        'error'=>$file['error'][$i],
                        'size'=>$file['size'][$i]
                      ];
                      $_POST[$field]=upload($single_file);
                      $multiple_items[]=$_POST;
                    }
                } else {
                    $_POST[$field]=upload($file);
                }
            }
        }
    }

    if (method_exists($class, 'preprocess')) {
        $_POST = $class->preprocess($_POST);
    }

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                if (!empty($multiple_items)) {
                    foreach ($multiple_items as $post) {
                        unset($post['action']);
                        $result[]=create($post, $class);
                    }
                } else {
                    unset($_POST['action']);
                    $result=create($_POST, $class);
                }
                break;

            case 'duplicate':
                unset($_POST['action']);
                $record = $class->find($_POST['id']);
                $record->replica();
                break;

            case 'link':
                unset($_POST['action']);
                $record = $class->find($_POST['id']);
                if (isset($class->linkages)) {
                    foreach ($class->linkages as $linkage) {
                        if (isset($_POST[$linkage])) {
                            $links = json_decode($_POST[$linkage]);
                            if (is_array($links)) {
                                $record->$linkage()->attach($links);
                            } else {
                                $record->$linkage()->attach([$links]);
                            }
                            // $record->touch();
                        }
                    }
                }
                if (isset($class->relations)) {
                    $result = $class->with($class->relations)->find($record->id);
                } else {
                    $result = $class->find($record->id);
                }
                break;

            case 'unlink':
                unset($_POST['action']);
                $record = $class->find($_POST['id']);
                if (isset($class->linkages)) {
                    foreach ($class->linkages as $linkage) {
                        if (isset($_POST[$linkage])) {
                            $links = json_decode($_POST[$linkage]);
                            if (is_array($links)) {
                                $record->$linkage()->detach($links);
                            } else {
                                $record->$linkage()->detach([$links]);
                            }
                            // $record->touch();
                        }
                    }
                }
                if (isset($class->relations)) {
                    $result = $class->with($class->relations)->find($record->id);
                } else {
                    $result = $class->find($record->id);
                }

                break;

            case 'edit':
                unset($_POST['action']);
                $record = $class->find($_POST['id']);
                $record->fill($_POST);

                if (isset($class->crosses)) {
                    foreach ($class->crosses as $cross) {
                        if (!isset($_POST[$cross])) {
                            $_POST[$cross] = [];
                        }
                        if (is_array($_POST[$cross])) {
                            $record->$cross()->detach();
                            $record->touch();
                            $sequence = 0;
                            foreach ($_POST[$cross] as $posted) {
                                $record->$cross()->attach([$posted]);
                            }
                        }
                    }
                }

                if (isset($class->linkages)) {
                    foreach ($class->linkages as $linkag) {
                        if (isset($_POST[$linkag])) {
                            if (is_array($_POST[$linkag])) {
                                foreach ($_POST[$linkag] as $posted) {
                                    $record->$linkag()->attach([$posted]);
                                    $record->touch();
                                }
                            } else {
                                $record->$linkag()->attach([$_POST[$linkag]]);
                                $record->touch();
                            }
                        }
                    }
                }

                if (isset($class->relations)) {
                    foreach ($class->relations as $relation) {
                        if (!isset($_POST[$relation])) {
                            $_POST[$relation] = [];
                        }
                        if (!isset($class->crosses) || !in_array($relation, $class->crosses)) {
                            $sequence = 0;
                            if (array_key_exists(0, $_POST[$relation])) {
                                foreach ($_POST[$relation] as $posted) {
                                    $record->$relation()->where('id', $posted['id'])->update($posted);
                                }
                            } elseif (sizeof($_POST[$relation])) {
                                $record->$relation()->where('id', $_POST[$relation]['id'])->update($_POST[$relation]);
                            }
                        }
                    }
                    $record->save();
                    $result = $record->load($class->relations);
                } else {
                    $record->save();
                    $result = $record;
                }
                break;

            case 'delete':
                
                $class->find($_POST['id'])->delete();
                break;

            case 'reorder':
                if (isset($_POST['sequence'])) {
                    foreach ($_POST['sequence'] as $id => $seq) {
                        $record = $class->find($id);
                        $record->sequence = $seq;
                        $record->save();
                    }
                }
                break;
        }
    } else {
        $data = $class;
        $total = $class;
        if (isset($class->relations)) {
            $data = $data->with($class->relations);
        }

        if (isset($filters['spots_campaign_id'])) {
            $campaign = Campaign::find($filters['spots_campaign_id']);
            $data = $data->with('campaign_placements.photos', 'other_placements')->whereHas('campaigns', function ($q) use ($filters) {
                $q->where('campaign_id', $filters['spots_campaign_id']);
            });

            $total = $total->whereHas('campaigns', function ($q) use ($filters) {
                $q->where('campaign_id', $filters['spots_campaign_id']);
            });
        }

        if (!isset($class->filesdb)) {
            foreach ($filters as $filter => $value) {                
                if ($class->getConnection()->getSchemaBuilder()->hasColumn($class->getTable(), $filter) && !is_subclass_of($class,'Jenssegers\Mongodb\Eloquent\Model')) {
                    $data = $data->where($filter, $value);
                    $total = $total->where($filter, $value);
                } else {                    
                    if (isset($datafilters[$filter]) && is_callable($datafilters[$filter])) {
                        $data = $datafilters[$filter]($value, $data);
                        $total = $datafilters[$filter]($value, $total);
                    }
                }
            }
        }

        if (!empty($_GET['length'])) {
            $limit = (int)$_GET['length'];
            $data = $data->limit($limit);
        }
        if (isset($_GET['start'])) {
            $offset = (int)$_GET['start'];
            $data = $data->offset($offset);
            $total = $total->count();
            $result['recordsTotal'] = $total;
            $result['recordsFiltered'] = $total;
        }

        if (!empty($_GET['order'])) {
            $data = $data->orderBy($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);
        }
        // print_r($data->toSql());
        // die();
        $result['data'] = $data->get();
    }

    if (method_exists($class, 'postprocess')) {
        $result = $class->postprocess($result, $filters);
    }
}

die(json_encode($result));
