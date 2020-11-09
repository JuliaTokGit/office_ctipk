<?php
use Kubia\AccountingDocuments\Group;
use Kubia\Accounting\GlAccount;
use Kubia\Accounting\Currency;

$separator = '';

function hideInput($input, $value){
    global $context;
    if (isset($context['page']->properties->modal)){
      foreach($context['page']->properties->modal as &$modal){
          foreach($modal['body']['fields'] as &$field){
              if (isset($field['property']) && $field['property']==$input){
                  $field=['hidden'=>$input,'value'=>$value];
              }
          }
      }
    }
}

if (isset($filters['gl_account_id'])) {
    $gl_account = GlAccount::find($filters['gl_account_id']);
    $context['page']->sub_header .= $separator.'GL account: '.$gl_account->id??''.' '.$gl_account->name??''.' ';
    hideInput('gl_account_id', $gl_account->id);
    $separator = ' | ';
}

if (isset($filters['currency_id'])) {
    $currency = Currency::find($filters['currency_id']);
    $context['page']->sub_header .= $separator.'Currency: '.$currency->name??''.' ';
    hideInput('currency_id', $currency->id);
    $separator = ' | ';
}


if (isset($filters['account_id'])) {
    $context['page']->sub_header .= $separator.'Account id: '.$filters['account_id']??''.' ';
    hideInput('account_id', $filters['account_id']);
    $separator = ' | ';
}


if (isset($filters['transaction_id'])) {
    $context['page']->sub_header .= $separator.'Transaction id: '.$filters['transaction_id']??''.' ';
    hideInput('transaction_id', $filters['transaction_id']);
    $separator = ' | ';
}

if (isset($filters['group_id'])) {
    $group = Group::find($filters['group_id']);
    $context['page']->sub_header .= $separator.'Type group: '.$group->name??''.' ';
    hideInput('group_id', $group->id);
    $separator = ' | ';
}


if (isset($filters['paysheet_id'])) {
    $context['page']->sub_header .= $separator.'Paysheet: '.$filters['paysheet_id']??''.' ';
    hideInput('paysheet_id', $filters['paysheet_id']);
    $separator = ' | ';
}

if (isset($filters['gl_account_id'])) {
    $gl_account = GlAccount::find($filters['gl_account_id']);
    $context['page']->sub_header .= $separator.'GL account: '.$gl_account->id??''.' '.$gl_account->name??''.' ';
    hideInput('gl_account_id', $gl_account->id);
    $separator = ' | ';
}

if (isset($filters['order_id'])) {    
    $context['page']->sub_header .= $separator.'Заявка: '.$filters['order_id']??''.' ';
    $context['header_content']=$context['header_content']??''.'<a href="/order/id/'.$filters['order_id'].'" class="btn btn-complete m-b-10">Вернуться в заявку</a> ';    
    hideInput('Код_Заявки', $filters['order_id']);
    $separator = ' | ';
}


if (isset($filters['order_back'])) {    
    $context['page']->sub_header .= $separator.'Заявка: '.$filters['order_back']??''.' ';
    $context['header_content']=$context['header_content']??''.'<a href="/order/id/'.$filters['order_back'].'" class="btn btn-complete m-b-10">Вернуться в заявку</a> ';        
    $separator = ' | ';
}