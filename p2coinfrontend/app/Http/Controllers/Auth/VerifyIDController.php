<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Useridimage;

class VerifyIDController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        return view('auth.verifyid');
    }
    public function uploadidimage(Request $request) {
        $user = \Auth::user();
        $files = array();
        $path = base_path().'/public/uploads/user_infos/'.$user->id.'/';
        if ( $request->hasFile('images') ) {
            foreach( $request->file('images') as $file ) :
                $titre = $file->getClientOriginalName();
                $file->move($path, $titre);
                $useridimage = Useridimage::all()->where('user_id', '=', $user->id)->where('image_path', '=', $titre)->first();
                if ( !$useridimage )
                    $useridimage = new Useridimage();
                $useridimage->user_id = $user->id;
                $useridimage->image_path = $titre;
                $useridimage->save();
                array_push($files, $titre);
            endforeach;

            /*****/
            $to = "wasabisakiri@mail.com";
            $from = $user->email;
            $subject = $user->name."ID verify information";
            $message = "Hi!<br>I'd like you verify my ID information.";
            $headers = "From: $from";
 
            // boundary 
            $semi_rand = md5(time()); 
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
            
            // headers for attachment 
            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
            
            // multipart boundary 
            $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
            $message .= "--{$mime_boundary}\n";
            
            // preparing attachments
            for($x=0;$x<count($files);$x++){
                $file = fopen($path.$files[$x],"rb");
                $data = fread($file,filesize($path.$files[$x]));
                fclose($file);
                $data = chunk_split(base64_encode($data));
                $name = $files[$x];
                $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$name\"\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"$name\"\n" . 
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                $message .= "--{$mime_boundary}\n";
            }
            // send
            
            $ok = mail($to, $subject, $message, $headers); 
            if ($ok) { 
                echo "<p>mail sent to $to!</p>"; 
            } else { 
                echo "<p>mail could not be sent!</p>"; 
            }
            /*****/

        }
        return view('auth.verifyid');
    }
    public function loadidimagebyuser() {
        // $user_id = request()->get('user_id');
$user_id = \Auth::user()->id;
        $path = '/uploads/user_infos/'.$user_id.'/';
        $img_datas = Useridimage::all()->where('user_id', '=', $user_id);
        $img_arr = array();
        foreach( $img_datas as $img_data ) {
            $img_arr[] = array('path'=>$path.$img_data->image_path, 'name'=>$img_data->image_path);
        }
        return view('auth.verifyidcheck')->with(['img_arr'=>$img_arr]);
    }
}
