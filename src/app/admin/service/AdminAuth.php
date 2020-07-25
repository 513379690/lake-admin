<?php

namespace app\admin\service;

use think\facade\Env;

use lake\Str;

use app\admin\Model\Admin as AdminModel;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthGroupAccess as AuthGroupAccessModel;
use app\admin\Model\AuthRule as AuthRuleModel;
use app\admin\model\AuthRuleAccess as AuthRuleAccessModel;
use app\admin\model\AuthRuleExtend as AuthRuleExtendModel;
use app\admin\service\Auth as AuthService;

/**
 * 管理员验证
 *
 * @create 2020-7-25
 * @author deatil
 */
class AdminAuth
{
    
    /**
     * 权限检测
     *
     * @create 2020-7-25
     * @author deatil
     */
    public static function instance($userConfig = [])
    {
        static $authList = [];
        
        $defaultConfig = [
            'AUTH_ON' => true, // 认证开关
            'AUTH_TYPE' => 1, // 认证方式，1为实时认证；2为登录认证。
            'AUTH_USER' => (new AdminModel)->getName(), // 用户信息表
            'AUTH_GROUP' => (new AuthGroupModel)->getName(), // 用户组数据表名
            'AUTH_GROUP_ACCESS' => (new AuthGroupAccessModel)->getName(),
            'AUTH_RULE' => (new AuthRuleModel)->getName(), // 权限规则表
            'AUTH_RULE_ACCESS' => (new AuthRuleAccessModel)->getName(), // 权限规则关系表
            'AUTH_RULE_EXTEND' => (new AuthRuleExtendModel)->getName(), // 扩展表
        ];
        $config = array_merge($defaultConfig, $userConfig);
        
        $authId = Str::toGuidString($config);
        if (isset($authList[$authId])) {
            return $authList[$authId];
        }
        
        $authList[$authId] = new AuthService($config);
        return $authList[$authId];
    }
    
    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     *
     * @create 2020-7-25
     * @author deatil
     */
    public static function checkRule($rule, $type = AuthRule::RULE_URL, $mode = 'url', $relation = 'or')
    {
        $config = config('app.auth');
        $Auth = static::instance($config);
        
        if (!$Auth->check($rule, Env::get('admin_id'), $type, $mode, $relation)) {
            return false;
        }
        return true;
    }

}
