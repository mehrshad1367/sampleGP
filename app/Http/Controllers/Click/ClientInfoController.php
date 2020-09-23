<?php

namespace App\Http\Controllers\Click;


use App\Artical;
use App\Http\Controllers\Controller;
use App\Http\Models\Visit;
use Illuminate\Http\Request;

class ClientInfoController extends Controller
{
    public function click($id)
    {
        $article = Artical::findOrFail($id);

        $visit=new Visit();
        $visit->instance_id=$article->id;
        $visit->instance_category=$article->category;
        $visit->ip=$this->getRealIpAddr();
        $visit->device=request()->header('User-Agent');
        $visit->browser=$this->getBrowser();
        $visit->platform=$this->getOS();
        $visit->save();
        return response()->json(array(
            'success'=>true,
        ));
    }
    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getOS() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform =   "";
        $os_array =   array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ( $os_array as $regex => $value ) {
            if ( preg_match($regex, $user_agent ) ) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    function getBrowser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser        = "Bilinmeyen Tarayıcı";
        $browser_array  = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ( $browser_array as $regex => $value ) {
            if ( preg_match( $regex, $user_agent ) ) {
                $browser = $value;
            }
        }
        return $browser;
    }
}

