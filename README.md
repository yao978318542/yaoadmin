# yaoadmin
简简单单的后台
#修改vendor/wenhainan/thinkphp6-auth/src/Auth.php getAuthList方法
`
$map = [
            //['type','=',$type],
            ['id','in', $ids],
            ['status','=',1],
        ];
//@(eval('$condition=(' . $command . ');'));
                $condition =$command;
                `

