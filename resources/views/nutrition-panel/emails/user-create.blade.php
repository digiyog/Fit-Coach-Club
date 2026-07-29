<html>
    <head>
        <title>Welcome Mail</title>
    </head>

    <body>
        <table style="width:100%">
            <tr>
                <td>
                    <img src="{{$template['template_logo']}}" style="height:100px; width:100px;"/>
                </td>
            </tr>
            <tr>
                <td>Welcome {{$data['name']}},</td>
            </tr>
            <tr>
                <td>
                    Thank your for registering with us as a {{$data['role_name']}}. Kindly login by clicking following link. Here is your login credential below:
                </td>
            </tr>
            <tr>
                <td>
                    @if(isset($data['login_url']))
                        <div>
                            Login Url : {{$data['login_url']}}
                        </div>
                    @endif
                    <div>
                        Email : {{$data['email']}}
                    </div>
                    <div>
                        Password : {{$data['password']}}
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>