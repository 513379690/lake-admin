<?php

namespace app\admin\model;

use app\admin\service\Admin as AdminService;

/**
 * 操作日志
 *
 * @create 2019-7-9
 * @author deatil
 */
class AdminLog extends ModelBase
{
    // 设置当前模型对应的数据表名称
    protected $name = 'admin_log';
    
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function getIpAttr($value)
    {
        return long2ip($value);
    }

    /**
     * 记录日志
     * @param type $message 说明
     * @param  integer $status  状态
     */
    public function record($message, $status = 0)
    {
        $adminId = AdminService::instance()->isLogin();
        if ($adminId > 0) {
            $adminInfo = env("admin_info");
            $adminUsername = $adminInfo['username'];
        } else {
            $adminId = 0;
            $adminUsername = '';
        }
    
        $data = [
            'id' => md5(time().mt_rand(10000, 99999)),
            'admin_id' => $adminId,
            'admin_username' => $adminUsername,
            'info' => "{$message}",
            'method' => request()->method(),
            'url' => request()->url(),
            'ip' => request()->ip(1),
            'useragent' => request()->server('HTTP_USER_AGENT'),
            'status' => $status,
        ];
        return $this->save($data) !== false ? true : false;
    }

    /**
     * 删除一个月前的日志
     * @return boolean
     */
    public function deleteAMonthago()
    {
        $status = $this->where('create_time', '<= time', time() - (86400 * 30))->delete();
        return $status !== false ? true : false;
    }

}
