<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\DataTables\UsersDataTable;
use DataTables;
use Session;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    { 
        if ($request->ajax()) {
            $limit = 10;
            $data = User::whereNull('deleted_at')->limit($limit);
            //$btn = '';
            // $data = $query->offset($data)->limit($limit);
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('phone', function($data){ 
                        return $data->phone;
                    })
                    ->addColumn('email', function($data){  
                        return $data->email;
                    })
                    ->addColumn('action', function($data){
                        return
                            '<div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu"> 

                                    <li><a href="#" data-id="'.$data->email.'_'.$data->name.'" id="email_send" data-toggle="modal" data-target="#modal-default">Mail Send</a></li>
                                    <li><a href="' . route('users.show', $data->id) .'">Show</a></li>
                                    <li><a href="' . route('users.edit', $data->id) .'">Edit</a></li>
                                    <li><a href="#" data-id="'.$data->id.'" data-url="'. route('users.destroy', $data->id).'" id="destroy">Delete</a></li>
                                </ul>
                            </div>';
                    })
                    ->rawColumns(['phone','action','email'])
                    ->make(true);
        } 
        return view('backend.users.mail');
        //return $dataTable->render('backend.users.index');
    }
    

    // public function index(UsersDataTable $dataTable)
    // { 
    //     return $dataTable->render('backend.users.index');
    // }
    
    // public function index__()
    // { 
    //     return view('backend.users.index');
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.users.create');
    }
 
    public function store_validation_rules($data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required'], 
            'password' => 'min:6|required_with:confirmed_password|same:confirmed_password',
            'confirmed_password' => 'min:6|required'
        ],
        [
            'name.required' => 'Name field is required.',
            'password.required' => 'Password field is required.',
            'confirmed_password.required' => 'Confirmed Password field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be email address.',
            'phone.required' => 'Phone field is required'
        ]); 
        return $validator;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $this->store_validation_rules($request->all());
        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }
        // dd($request->all());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('users.index')->with('success', 'User has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('backend.users.edit', compact('user'));
    }

    public function update_validation_rules($data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required']
        ],
        [
            'name.required' => 'Name field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be email address.',
            'phone.required' => 'Phone field is required'
        ]); 
        return $validator;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validation = $this->update_validation_rules($request->all());
        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'User has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success'=>1]);
    }

    public function user_mail_send(Request $request)
    {
        //dd($request->all());

        // require 'path/to/PHPMailer/src/Exception.php';
        // require 'path/to/PHPMailer/src/PHPMailer.php';
        // require 'path/to/PHPMailer/src/SMTP.php';

        //require 'vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'events.meca@gmail.com';                     //SMTP username
            $mail->Password   = 'gjfxhskmwfrbdges';                               //SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('events.meca@gmail.com', 'User Info');
            $mail->addAddress($request->email, $request->name);     //Add a recipient
            // $mail->addAddress('alhassan.cse@gmail.com');               //Name is optional
            $mail->addReplyTo('no-replay@gmail.com', 'No-Replay');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $request->subject;
            $mail->Body    = $request->message;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            // return response()->json(['success'=>1]);
            echo 'Message has been sent';
        } catch (Exception $e) {
            // dd($e);
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
    

}
