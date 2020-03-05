<?php

namespace app\admin\controller;

use think\Db;

use app\admin\model\AdminLog as AdminlogModel;

/**
 * 管理日志
 *
 * @create 2019-8-4
 * @author deatil
 */
class AdminLog extends Base
{
	
	/**
	 * 框架构造函数
	 *
	 * @create 2019-8-4
	 * @author deatil
	 */
    protected function initialize()
    {
        parent::initialize();
		
        $this->AdminlogModel = new AdminlogModel;
    }

	/**
	 * 日志首页
	 *
	 * @create 2019-8-4
	 * @author deatil
	 */
    public function index()
    {
        if ($this->request->isAjax()) {
            $limit = $this->request->param('limit/d', 20);
            $page = $this->request->param('page/d', 1);

			$map = $this->buildparams();
			
            $method = $this->request->param('method/s', '');
			if (!empty($method)) {
				$map[] = ['method', '=', $method];
			}
			
            $data = $this->AdminlogModel
				->where($map)
                ->page($page, $limit)
                ->order('id DESC')
                ->select();
				
            $total = $this->AdminlogModel
				->where($map)
				->order('id DESC')
				->count();
				
            $result = [
				"code" => 0, 
				"count" => $total, 
				"data" => $data,
			];
			
            return json($result);
        }
        return $this->fetch();
    }

	/**
	 * 详情
	 *
	 * @create 2019-7-28
	 * @author deatil
	 */
    public function view()
    {
		if (!$this->request->isGet()) {
			$this->error('访问错误！');
		}
		
        $id = $this->request->param('id');
		if (empty($id)) {
			$this->error('信息ID错误！');
		}
		
		$data = $this->AdminlogModel
			->where([
				"id" => $id,
			])
			->find();
		if (empty($data)) {
			$this->error('信息不存在！');
		}
		
		$this->assign("data", $data);
		return $this->fetch();		
    }

	/**
	 * 删除一个月前的操作日志
	 *
	 * @create 2019-8-4
	 * @author deatil
	 */
    public function deletelog()
    {
		if (!$this->request->isPost()) {
			$this->error('请求错误！');
		}
		
		$status = $this->AdminlogModel->deleteAMonthago();
        if ($status === false) {
            $this->error("删除日志失败！");
        }
		
		$this->success("删除日志成功！");
    }

}
