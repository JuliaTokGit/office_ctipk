<?php
use Carbon\Carbon;

$datafilters=[

	'datefrom'=>function($value,$query){
		return $query->where('created_at','>',$value);
	},

	'dateto'=>function($value,$query){
		return $query->where('created_at','<',$value.' 23:59:59');
	},


	'order_status_id_bak'=>function($value,$query){
		$order_status=OrderStatus::find($value);
		return $query->with('status')->withPivot('order_status_id')->where('')->orderBy('');
	},


	'order_status_id'=>function($value,$query){
		$order_status=OrderStatus::find($value);
		return $query->with('status')
			->whereHas('statuses', function($q) use ($value){
				$q->where('step',$value);
			});
	},

	'status_more'=>function($value,$query){
		return $query->where('status_id','>',$value);
	},

	'range'=>function($value,$query){
		$dates=explode(' - ',urldecode($value));
    	$start = new Carbon($dates[0]);
    	$end = new Carbon($dates[1]);
		return $query->range($start,$end);
	},

	'shop_id'=>function($value,$query){
		return $query->shop($value);
	},

	'paysheet_status_id'=>function($value,$query){
		// $paysheet_status=\Kubia\Payroll\Status::find($value);
		return $query->with('statuses')->withPivot('created_at')->orderBy('paysheet_statuses.created_at', 'desc')->groupBy('paysheet_statuses.paysheet_id')->whereHas('statuses', function($q) use ($value){
				$q->orderBy('name', 'desc')->where('id',$value);
			});
		// return $query->with('statuses')
		// 	->whereHas('statuses', function($q) use ($value){
		// 		$q->orderBy('name', 'desc')->where('id',$value);
		// 	});
	},

	// 'group_id'=>function($value,$query){
	// 	return $query->where('group_id',$value);
	// },

];
