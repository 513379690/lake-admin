<?php

namespace app\admin\controller;

use think\Controller;

/**
 * 后台基础类
 *
 * @create 2019-7-15
 * @author deatil
 */
abstract class Base extends Controller
{
	/**
	 * 当前登录账号信息
	 *
	 * @create 2019-10-10
	 * @author deatil
	 */
    protected $_userinfo;
    
	/**
	 * 空操作
	 *
	 * @create 2019-10-10
	 * @author deatil
	 */
    public function _empty()
    {
        $this->error('该页面不存在！');
    }
	
	/**
	 * 初始化
	 *
	 * @create 2019-10-10
	 * @author deatil
	 */
    protected function initialize()
    {
        parent::initialize();
		
		$view_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
		$this->view->config('view_path', $view_path);
		
		$this->_userinfo = env('userinfo');
    }

	/**
	 * 生成查询所需要的条件,排序方式
	 *
	 * @create 2019-10-10
	 * @author deatil
	 */
    protected function buildparams()
    {
        $search_field = $this->request->param('search_field/s', '', 'trim');
        $keyword = $this->request->param('keyword/s', '', 'trim');
       
		$this->assign("search_field", $search_field);
		$this->assign("keyword", $keyword);

        $filter_time = $this->request->param('filter_time/s', '', 'trim');
        $filter_time_range = $this->request->param('filter_time_range/s', '', 'trim');
       
		$this->assign("filter_time", $filter_time);
		$this->assign("filter_time_range", $filter_time_range);

        $map = [];
        // 关键词搜索
        if ($search_field != '' && $keyword !== '') {
            $map[] = [$search_field, 'like', "%$keyword%"];
        }

        // 时间范围搜索
        if ($filter_time && $filter_time_range) {
            $filter_time_range = str_replace(' - ', ',', $filter_time_range);
            $arr = explode(',', $filter_time_range);
            !empty($arr[0]) ? $arr[0] : date("Y-m-d", strtotime("-1 day"));
            !empty($arr[1]) ? $arr[1] : date('Y-m-d', time());
            $map[] = [$filter_time, 'between time', [$arr[0] . ' 00:00:00', $arr[1] . ' 23:59:59']];
        }
		
        return $map;
    }

}
