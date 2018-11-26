<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tags;

class TagsController extends Controller
{
    protected $tags;
    public function __construct(Tags $tags)
    {
        $this->tags = $tags;
    }

    public function find(Request $request)
    {
        
        $term = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        $tags = $this->tags->where('tags_name', 'like', '%' . $term . '%')->get();

        $formatted_tags = [];

        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->id, 'text' => $tag->tags_name];
        }

        return \Response::json($formatted_tags);
    }

    public function postAddNewTags(Request $request)
    {
        $result = ['status' => '', 'message' => ''];
        $data['tags_name'] = $request->input('new_tags');
        $check = $this->tags->checkExist($data['tags_name']);
        if($check){
            $result['status'] = 'duplicate';
            $result['message'] = 'Tag exitst';
        } else {
            if($this->tags->insert($data)){
                $result['status'] = 'success';
                $result['message'] = 'Success!';
            } else {
                $result['status'] = 'error';
                $result['message'] = 'Error';
            }
        }
        return \Response::json($result);
    }

}
