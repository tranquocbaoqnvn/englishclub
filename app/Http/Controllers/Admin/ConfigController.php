<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;

class ConfigController extends Controller
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    public function config()
    {
        $data = [];
        $list = $this->config->get();
        foreach($list as $item) {
            $data[$item->key] = $item->value;
        }

        return view('admin/config', $data);
    }

    public function postConfig(Request $request)
    {
        $request->validate([
            'slogan' => 'required|max:255',
            'phone' => 'required|numeric',
            'member_number' => 'required|numeric',
            'active_years' => 'required|numeric',
        ]);
        
        $data = array(
            array('key'=>'slogan', 'value'=> $request->input('slogan')),
            array('key'=>'phone', 'value'=> $request->input('phone')),
            array('key'=>'member_number', 'value'=> $request->input('member_number')),
            array('key'=>'active_years', 'value'=> $request->input('active_years')),
        );
        $this->config->truncate();
        $this->config->insert($data);
        
        return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_update_success')]);
    }

}
