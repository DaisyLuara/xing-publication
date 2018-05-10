<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = snake_case($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return str_plural($snake_case_name);
}

function make_tree($arr)
{

    //@todo 行业类别 分类处理
    $refer = array();
    $tree = array();
    foreach ($arr as $k => $v) {
        $refer[$v['id']] = &$arr[$k];  //创建主键的数组引用
    }

    foreach ($arr as $k => $v) {
        $pid = $v['pid'];   //获取当前分类的父级id
        if ($pid == 0) {
            $tree[] = &$arr[$k];   //顶级栏目
        } else {
            if (isset($refer[$pid])) {
                $refer[$pid]['sub'][] = &$arr[$k];  //如果存在父级栏目，则添加进父级栏目的子栏目数组中
            }
        }
    }

    return $tree;
}