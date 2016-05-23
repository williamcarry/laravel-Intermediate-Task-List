<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller {

    protected $tasks;

    public function __construct(TaskRepository $tasks) {
        /*
         *     protected $routeMiddleware = [
          'auth' => \App\Http\Middleware\Authenticate::class,
          'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
          'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
          'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
          ];
         * 上面是从Kernel.php拷贝出来的。
         * $this->middleware('auth');一看就知道这句话要用'auth' => \App\Http\Middleware\Authenticate::class,这个类
         * 然后看看这个类有什么，主要意思就是没登陆的都去登录。。别在这里晃悠
         */
        $this->middleware('auth');
        $this->tasks = $tasks;
    }

    public function index(Request $request) {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    public function store(Request $request) {
        /*
         * <input type="text" name="name" id="task-name" class="form-control">
         * 模板当中name="name"和'name' => 'required|max:255',的name是一个，名称必须一样
         */
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        /*
          echo "<pre>";
          print_r($request->user());die;
          $request->user()->tasks()这里调用就是下面这个方法。。文件\app\User.php
          public function tasks() {
          return $this->hasMany(Task::class);
          }
         * 
         * 
         * 
         * \app\Task.php 把里面的protected $fillable = ['name'];注释掉，这回报错
         * 'name' => $request->name, 前面那个name就是protected $fillable = ['name']
         * $fillable也是一样，名字不可以改，大小写不可以改，是程序设定好的特殊变量
         * $request->namde 就是你传递过来的删除
         * 
         */
        /*
         * <input type="text" name="name" id="task-name" class="form-control">
         * 模板当中name="name"和$request->name,的name是一个，名称必须一样
         * ->create 怎么来的因为class Task extends Model 所以可以直接用model里的所有函数
         * model的命名空间namespace Illuminate\Database\Eloquent;
         * 自己打开看看就知道了
         */
        $request->user()->tasks()->create([
            'name' => $request->name, //前面黄色的name是表字段
        ]);

        return redirect('/tasks');
    }

public function destroy(Request $request, Task $task)
{
    //这句话的意思，验证下当前用户是不是删除了自己的task，如果试图删除别人的，就会验证不通过
    $this->authorize('destroy', $task);//这里面的 'destroy' 是\app\Policies\TaskPolicy.php里的函数

    $task->delete();//delete函数怎么来的。。ctlr+鼠标左键点击试试
    //因为class Task extends Model 所以可以直接用model里的所有函数

    return redirect('/tasks');
}

}
