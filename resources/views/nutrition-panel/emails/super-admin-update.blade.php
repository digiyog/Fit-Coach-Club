<html>
    <head>
        <title>Information Update Mail</title>
    </head>

    <body>
        <table style="width:100%">
            <tr>
                <td>
                    <img src="{{$template['template_logo']}}" style="height:100px; width:100px;"/>
                </td>
            </tr>
            <tr>
                <td>Hello {{$data['name']}},</td>
            </tr>
            <tr>
                <td>
                    We have received an information update for your profile. Here is your updated login credential below:
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