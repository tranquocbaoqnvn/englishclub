<?php

namespace App\Http\Controllers\Admin;
use Yajra\Datatables\Datatables;
use App\Models\Posts;
use App\Models\Tags;
use App\Models\TagsPosts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;

class PostController extends Controller
{
    protected $posts;
    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }
    public function posts()
    {
        return view('admin/Posts/posts');
    }

//----------------------------Add a News---------------------------------------

    public function addPosts()
    {
        return view('admin/Posts/add');
    }

    public function postAddPosts(Request $request)
    {
        $request->validate([
            'posts_image' => 'required|image|mimes:jpg,png, JPG, PNG',
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'url' => 'required',
        ]);

        if($request->hasFile('posts_image'))
        {
            $file = $request->file('posts_image');
            $destinationPath = './upload/posts/';

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $newfilename = 'posts_'.time().'.'.$extension;

            $uploadSuccess = $request->file('posts_image')->move($destinationPath, $newfilename);         
        }
        $data = array(
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "content" => $request->input('content'),
            "url" => $request->input('url'),
            "image" => $newfilename
        );

        $id = $this->posts->insertGetId($data);
        $tagsposts = new TagsPosts;
        

        foreach($request->input('tags_list') as $tagId){
            $data = array(
                "tags_id" => $tagId,
                "posts_id" => $id
            );
            $tagsposts->insert($data);
        }

        return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_add_success')]);

    }
//----------------------------Edit News---------------------------------------
    public function postEditPosts(Request $request, $id)
    {  
        $request->validate([
            'news_image' => 'image|mimes:jpg,png, JPG, PNG',
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'url' => 'required',
        ]);
        if($request->hasFile('posts_image'))
        {
            $file = $request->file('posts_image');
            $destinationPath = './upload/posts/';

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $newfilename = 'posts_'.time().'.'.$extension;

            $uploadSuccess = $request->file('posts_image')->move($destinationPath, $newfilename);         
        }

        $data = array(
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "content" => $request->input('content'),
            "url" => $request->input('url'),
        );
        if(isset($newfilename) == TRUE)
        {
            $data["image"] = $newfilename;
        }
        
        $this->posts->update($data);
        $tagsnews = new TagsNews;
        $tagsnews->where('news_id', $id)->delete();

        foreach($request->input('tags_list') as $tagId){
            $data = array(
                "tags_id" => $tagId,
                "news_id" => $id
            );
            $tagsnews->insert($data);
        }

       return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_update_success')]);
    }

    //----------------------------Delete News---------------------------------------

    public function deletePosts($id)
    {
        $this->posts->where('id', $id)->delete();
        return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_delete_success')]);
    }
//--------------------------------------Get grid datatable-------------------------------------------------------
    public function getList()
    {

        return $datatable = Datatables::of(Posts::query())
            ->addColumn('action', function ($posts) {
                return '<a href="'.route('dashboard:editPosts', ['id' => $posts->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="'. route('dashboard:deletePosts', ['id' => $posts->id]) .'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->make(true);
    }
//---------------------------------Show Edit Post page------------------------------------------------------------
    public function editPosts($id)
    {
        $data = [];
        $item = $this->posts->where('id', $id)->first();

        $data['id'] = $item->id;
        $data['title'] = $item->title;
        $data['description'] = $item->description;
        $data['content'] = $item->content;
        $data['url'] = $item->url;
        $data['tags'] = $item->tags;
        $data['posts_image'] = $item->image;

        return view('admin/Posts/edit', $data);
    }
}

