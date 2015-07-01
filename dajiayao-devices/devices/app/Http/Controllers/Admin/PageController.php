<?php namespace Dajiayao\Http\Controllers\Admin;

use Dajiayao\Http\Controllers\Controller;
use Dajiayao\Library\Weixin\Page;
use Dajiayao\Library\Weixin\ShakeAroundClient;
use Dajiayao\Model\Device;
use Dajiayao\Model\DevicePage;
use Dajiayao\Model\WeixinDevice;
use Dajiayao\Model\WeixinPage;
use Dajiayao\Model\WeixinMp;
use Dajiayao\Model\App;
use Dajiayao\Services\PageService;
use Dajiayao\User;
use Input;
use Auth;
use Validator;
use Config;
use Session;
use J20\Uuid\Uuid;

class PageController extends Controller {

    /**
     * 微信页面列表
     * @author Hanxiang
     */
    public function index() {
        $wx_mps = self::getCurrentWxMps();

        $input = Input::all();
        if (isset($input['wx_mp_id']) && $input['wx_mp_id'] > 0) {
            $wx_mp_id = $input['wx_mp_id'];
            $wxpages = WeixinPage::where('wx_mp_id', $input['wx_mp_id']);//->get();
        } else {
            $wx_mp_id = null;
            $wxpages = WeixinPage::where('id', '>', 0);
        }

        $user = Auth::user();
        if ($user->role != User::ROLE_ADMIN) {
            $wxMpIDs = [];
            $apps = App::where('user_id', $user->id)->get();
            foreach ($apps as $a) {
                $wxMps = $a->wxMp;
                foreach ($wxMps as $mp) {
                    array_push($wxMpIDs, $mp->id);
                }
            }
            $wxpages = $wxpages->whereIn('wx_mp_id', $wxMpIDs);
        }

        if(isset($input['kw']) && $input['kw'] != '') {
            $kw = $input['kw'];
            $wxpages = $wxpages->where(function ($query) use ($kw) {
                $query->where('title'  , 'like', "%".$kw."%")
                    ->orwhere('description', 'like', "%".$kw."%")
                    ->orwhere('comment', 'like', "%".$kw."%");
            });
        }

        $wxpages = $wxpages->orderBy('updated_at', 'desc')->get();
        foreach ($wxpages as $wxpage) {
            $device_page_count = DevicePage::where('wx_page_id', $wxpage->id)->count();
            if ($device_page_count > 0) {
                $wxpage->bind_dvc_sts = 1;
            } else {
                $wxpage->bind_dvc_sts = 0;
            }
        }

        return view('admin.wxpages.index')
            ->with('wxpages', $wxpages)
            ->with('wxmps', $wx_mps)
            ->with('kw',isset($input['kw']) ? $input['kw'] : '')
            ->with('wx_mp_id', $wx_mp_id);
    }

    /**
     * 增加页面
     * @author Hanxiang
     */
    public function add() {
        $wx_mps = self::getCurrentWxMps();

        return view('admin.wxpages.add')->with('wxmps', $wx_mps);
    }

    /**
     * 增加页面POST
     * @author Hanxiang
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @return view
     */
    public function addPost(PageService $pageService, ShakeAroundClient $shakeAroundClient) {
        $input = Input::all();
        $wx_mp_id = $input['wx-mp-id'];
        $title = $input['txt-title'];
        $subtitle = $input['txt-subtitle'];
        $url = $input['txt-url'];
        $comment = $input['txt-comment'];

        $validator = Validator::make($input, [
            'wx-mp-id' => 'required',
            'txt-title' => 'required',
            'txt-subtitle' => 'required',
            'txt-url' => 'required',
            'txt-comment' => ''
        ]);
        if ($validator->fails()) {
            return redirect('/admin/wxpages/add')
                ->with('wxmps', self::getCurrentWxMps())
                ->with('result', false)
                ->with('msg', '参数错误');
        }

        $file = Input::file('file-icon');
        $ext = $file->getClientOriginalExtension();
        $filename = Uuid::v4(false) . ".$ext";
        $file->move(public_path("pageicons"), $filename);
        $allFilename = public_path("pageicons/") . $filename;

        $wxMp = WeixinMp::find($wx_mp_id);
        if (!$wxMp) {
            return redirect('/admin/wxpages/add')
                ->with('wxmps', self::getCurrentWxMps())
                ->with('result', false)
                ->with('msg', '操作失败，公众号不存在');
        }
        $appid = $wxMp->appid;
        $appsecret = $wxMp->appsecret;

        $guid = Uuid::v4(false);
        $arrPage = [
            'wx_mp_id' => $wx_mp_id,
            'title' => $title,
            'description' => $subtitle,
            'comment' => $comment,
            'icon_url' => "local://" . $allFilename,
            'url' => Config::get('app.url').str_replace('GUID',$guid,Config::get("weixin.callback_url"))
        ];

        $page = new Page($arrPage);

        try {
            $page->page_url = $page->url;
            $pageId = $pageService->applyPageOnline($shakeAroundClient, $page, $appid, $appsecret);
        } catch(\Exception $e) {
            return redirect('/admin/wxpages/add')
                ->with('wxmps', self::getCurrentWxMps())
                ->with('result', false)
                ->with('msg', '操作失败。' . $e->getMessage());
        }

        $page->page_id = $pageId;
        unset($page->page_url);
        $page->guid = $guid;
        $page->url = empty($url) ? "" : $url;
        $id = $pageService->create($page);

        return redirect('/admin/wxpages')
            ->with('result', true)
            ->with('msg', "操作成功");
    }

    /**
     * 获取当前用户拥有的公众号
     * @author Hanxiang
     */
    private static function getCurrentWxMps() {
        $user = Auth::user();
        $wx_mps = [];
        if ($user->role == User::ROLE_COMMON_USER) {
            $apps = App::where('user_id', $user->id)->get();
            if ($apps) {
                foreach ($apps as $a) {
                    $wx_mps = WeixinMp::where('app_id', $a->id)->get();
                }
            }
        } else {
            $wx_mps = WeixinMp::all();
        }
        return $wx_mps;
    }

    /**
     * 修改页面
     * @author Hanxiang
     * @param $id
     * @return view
     */
    public function update($id) {
        $wxpage = WeixinPage::find($id);
        if (!$wxpage) {
            return redirect('/admin/wxpages')
                ->with('result', false)
                ->with('msg', "操作失败，页面不存在");
        }

        $wxmp = $wxpage->mp;
        return view('admin.wxpages.update')
            ->with('wxpage', $wxpage)
            ->with('wxmp', $wxmp);
    }

    /**
     * 修改页面 post
     * @author Hanxiang
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @return mixed
     */
    public function updatePost(PageService $pageService,ShakeAroundClient $shakeAroundClient) {
        $input = Input::all();

        $validator = Validator::make($input, [
            'wx-page-id' => 'required',
            'txt-title' => 'required',
            'txt-subtitle' => 'required',
            'txt-url' => 'required',
            'txt-comment' => ''
        ]);
        if ($validator->fails()) {
            return redirect('/admin/wxpages/update/' . $input['wx-page-id'])
                ->with('result', false)
                ->with('msg', '参数错误');
        }

        $wxPage = WeixinPage::find($input['wx-page-id']);
        if (!$wxPage) {
            return redirect('/admin/wxpages/update/' . $input['wx-page-id'])
                ->with('result', false)
                ->with('msg', '操作失败');
        }

        $pageArray = [];
        $pageArray['page_id'] = $wxPage->page_id;
        $pageArray['title'] = $input['txt-title'];
        $pageArray['description'] = $input['txt-subtitle'];
        $pageArray['url'] = $input['txt-url'];
        $pageArray['comment'] = $input['txt-comment'];

        if (Input::hasFIle('file-icon')) {
            $file = Input::file('file-icon');
            $ext = $file->getClientOriginalExtension();
            $filename = Uuid::v4(false) . ".$ext";
            $file->move(public_path("pageicons"), $filename);
            $allFilename = public_path("pageicons/") . $filename;
            try {
                $pageArray['icon_url'] = $pageService->converToUrlOnline($shakeAroundClient,'local://' . $allFilename);
            } catch(\Exception $e) {
                return redirect('/admin/wxpages/update/' . $input['wx-page-id'])
                    ->with('result', false)
                    ->with('msg', '操作失败 ' . $e->getMessage() . $e->getCode());
            }
        } else {
            $pageArray['icon_url'] = $wxPage->icon_url;
        }

        $page = new Page($pageArray);
        $page->page_url = $page->url;
        unset($page->url);
        $wxMp = WeixinMp::find($wxPage->wx_mp_id);
        $appid = $wxMp->appid;
        $appsecret = $wxMp->appsecret;
        try{
            $pageService->updatePageOnline($shakeAroundClient, $page, $appid, $appsecret);
        }catch (\Exception $e){
            return redirect('/admin/wxpages/update/' . $input['wx-page-id'])
                ->with('result', false)
                ->with('msg', '操作失败 ' . $e->getMessage() . $e->getCode());
        }

        unset($page->page_url);
        try{
            $pageService->update($wxPage, $page);
        } catch (\Exception $e){
            return redirect('/admin/wxpages/update/' . $input['wx-page-id'])
                ->with('result', false)
                ->with('msg', '操作失败 ' . $e->getMessage());
        }

        return redirect('/admin/wxpages')
            ->with('result', true)
            ->with('msg', "操作成功");
    }

    /**
     * 修改页面 post
     * @author Hanxiang
     * @param $id
     * @param ShakeAroundClient $shakeAroundClient
     * @param PageService $pageService
     * @return mixed
     */
    public function delete($id, ShakeAroundClient $shakeAroundClient, PageService $pageService) {
        $wx_page = WeixinPage::find($id);
        if (!$wx_page) {
            return redirect('/admin/wxpages')
                ->with('result', false)
                ->with('msg', "操作失败，页面不存在");
        }

        try {
            $pageService->deletePageOnline($shakeAroundClient, [$id]);
        } catch(\Exception $e) {
            return redirect('/admin/wxpages')
                ->with('result', false)
                ->with('msg', "操作失败" . $e->getMessage());
        }

        $pageService->delete([$id]);
        return redirect('/admin/wxpages')
            ->with('result', true)
            ->with('msg', "操作成功");
    }

    /**
     * 绑定页面
     * @author Hanxiang
     * @param $id
     * @return view
     */
    public function bind($id) {
        $wxpage = WeixinPage::find($id);
        if (!$wxpage) {
            return redirect('/admin/wxpages')
                ->with('result', false)
                ->with('msg', "操作失败，页面不存在");
        }

        $mp = $wxpage->mp;
        $wxDevices = WeixinDevice::where('wx_mp_id', $mp->id)->get();
        foreach ($wxDevices as $wxdvc) {
            $count = DevicePage::where('wx_device_id', $wxdvc->id)
                ->where('wx_page_id', $id)
                ->count();
            if ($count > 0) {
                $wxdvc->bind_status = 1;
            } else {
                $wxdvc->bind_status = 0;
            }

            $wxdvc->device = Device::where('wx_device_id', $wxdvc->id)->get();
        }
        return view('admin.wxpages.bind')
            ->with('id', $id)
            ->with('wxdevices', $wxDevices);
    }

    /**
     * 绑定页面POST
     * @author Hanxiang
     * @param PageService $pageService
     * @param ShakeAroundClient $shakeAroundClient
     * @return json
     */
    public function bindPost(PageService $pageService, ShakeAroundClient $shakeAroundClient) {
        $input = Input::all();
        $validator = Validator::make($input, [
            'id' => 'required',
            'wx_device_ids' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['result' => 0, 'msg' => '参数错误']);
        }
        if (!is_array($input['wx_device_ids'])) {
            return response()->json(['result' => 0, 'msg' => '参数错误']);
        }

        $wxpage = WeixinPage::find($input['id']);
        if (!$wxpage) {
            return response()->json(['result' => 0, 'msg' => '微信页面不存在']);
        }

        $wxMp = WeixinMp::find($wxpage->wx_mp_id);
        $appid = $wxMp->appid;
        $appsecret = $wxMp->appsecret;
        try {
            $pageService->bindDevice($shakeAroundClient, $wxpage, $input['wx_device_ids'], 1, 1, $appid, $appsecret);
        } catch(\Exception $e) {
            return response()->json(['result' => 0, 'msg' => $e->getMessage()]);
        }

        Session::flash('result', true);
        Session::flash('msg', "操作成功");
        return response()->json(['result' => 1, 'msg' => '操作成功']);
    }
}
