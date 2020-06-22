<?php
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Upload extends Spacewind\Models\Upload
{
    use HybridRelations;
    protected $connection = 'default';
    public function preprocess($request)
    {
        global $uploaded;
        if (isset($uploaded)) {
            $request['file_size'] = $uploaded->size;
            $request['content_type'] = $uploaded->type;
        }
        if (isset($request['action']) && $request['action']=='create') {
            if (isset($request['description']) && $request['description']=='') {
                unset($request['description']);
            }
            $request['action']="edit";
        }
        return $request;
    }
}
