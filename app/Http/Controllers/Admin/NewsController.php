<?php

namespace App\Http\Controllers\Admin;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Tags;
use App\Models\TagsNews;
use Intervention\Image\ImageManager;

class NewsController extends Controller
{
    //----------------------------Construct---------------------------------------
    protected $tags;
    protected $news;
    public function __construct(News $news, Tags $tags)
    {
        $this->news = $news;
        $this->tags = $tags;
    }
    //----------------------------Load list of News view---------------------------------------
    public function news()
    {
        return view('admin/News/news');
    }
    //----------------------------Get List Datatable---------------------------------------
    public function getList()
    {

        return $datatable = Datatables::of(News::query())
            ->addColumn('action', function ($news) {
                return '<a href="'.route('dashboard:editNews', ['id' => $news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="'. route('dashboard:deleteNews', ['id' => $news->id]) .'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->addColumn('tags', function ($news) {
                $tag = $news->tags;

                if($tag) {
                    $data = [];
                    foreach($tag as $tags){
                        $data[] = $tags->tags_name;
                    }
                    return $data;
                }
                return '';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
    }
    //----------------------------Load Add News View---------------------------------------
    public function addNews()
    {
        return view('admin/News/add');
    }

    //----------------------------Add a News---------------------------------------

    public function postAddNews(Request $request)
    {
        $request->validate([
            'news_image' => 'required|image|mimes:jpg,png, JPG, PNG',
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'url' => 'required',
        ]);
        if($request->hasFile('news_image'))
        {
            $file = $request->file('news_image');
            $destinationPath = './upload/news/';

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $newfilename = 'news_'.time().'.'.$extension;

            $uploadSuccess = $request->file('news_image')->move($destinationPath, $newfilename);         
        }



        $data = array(
            "title" => $request->input('title'),
            "description" => $request->input('description'),
            "content" => $request->input('content'),
            "url" => $request->input('url'),
            "image" => $newfilename
        );
        //----------------------------------set value for Tags_news table------------------------------
        $id = $this->news->insertGetId($data);
        $tagsnews = new TagsNews;

        foreach($request->input('tags_list') as $tagId){
            $data = array(
                "tags_id" => $tagId,
                "news_id" => $id
            );
            $tagsnews->insert($data);
        }
        return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_add_success')]);
    }

    //----------------------------Delete News---------------------------------------

    public function deleteNews($id)
    {
        $this->news->where('id', $id)->delete();
        return back()->with('alert', ['status' => 'success', 'message' => __('common.alert_delete_success')]);
    }

    //----------------------------Edit News---------------------------------------

    public function newsEdit($id)
    {
        
        $data = [];
        $item = $this->news->find($id);
        $tags = $item->tags()->get();

        $data['tags'] = $tags;
        $data['id'] = $item->id;
        $data['title'] = $item->title;
        $data['description'] = $item->description;
        $data['content'] = $item->content;
        $data['url'] = $item->url;
        $data['tags'] = $item->tags;
        $data['news_image'] = $item->image;
    
        return view('admin/News/edit', $data);
    }
    
    //----------------------------Edit postNews---------------------------------------

    public function postNews(Request $request, $id)
    {  
        $request->validate([
            'news_image' => 'image|mimes:jpg,png, JPG, PNG',
            'title' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'url' => 'required',
        ]);
        if($request->hasFile('news_image'))
        {
            $file = $request->file('news_image');
            $destinationPath = './upload/news/';

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $newfilename = 'news_'.time().'.'.$extension;

            $uploadSuccess = $request->file('news_image')->move($destinationPath, $newfilename);         
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
        
        $this->news->update($data);
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

    public function tinymceUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,png, JPG, PNG',       
        ]);

        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $destinationPath = './upload/tinyMCE/';

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $newfilename = 'tinyMCE_'.time().'.'.$extension;

            $uploadSuccess = $request->file('file')->move($destinationPath, $newfilename);         
        }
        return response()->json(['location' => '/upload/tinyMCE/' . $newfilename]);
    }   
    
    public function postGetTagsID(Request $request)
    {
        $id = $request->input('tags_id');
        $item = $this->tags->find($id);
        $getNews = $item->news()->get();
        return $datatable = Datatables::of($getNews)
            ->addColumn('action', function ($news) {
                return '<a href="'.route('dashboard:editNews', ['id' => $news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a href="'. route('dashboard:deleteNews', ['id' => $news->id]) .'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->addColumn('tags', function ($news) {
                $tag = $news->tags;

                if($tag) {
                    $data = [];
                    foreach($tag as $tags){
                        $data[] = $tags->tags_name;
                    }
                    return $data;
                }
                return '';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
    }

}
