<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\User;

class AttendancesController extends Controller
{
    public function __construct() {
       $this->middleware('auth');
    }

    public function scan(Request $request)
    {
        $this->validate($request,[
            'qrcode' => 'required',
        ]);

        $qr = $request->input('qrcode');
        $in = $request->input('in');
        $out = $request->input('out');
        $date = date('d-m-y');

        $data = [
            'qr' => $qr,
            'in' => $in,
            'out' => $out,
            'date' => $date,
        ];

        $employee = User::where('qrcode', $qr)->first();

        if($employee){
            $check = Attendance::where('qr', $qr)->where('date', $date)->first();
            // dd($check);
            if($check){
                if($check->in == null && $check->out != null){
                    $save = Attendance::where('qr', $qr)->where('date', $date)->update(['in' => $in]);
                }else{
                    $save = Attendance::where('qr', $qr)->where('date', $date)->update(['out' => $out]);
                }
            }else{
                $save = Attendance::create($data);
            }
            return response()->json(['attendance' => $save, 'employee' => $employee], 201);
        }else{
            return response()->json(['message' => 'QR not found'], 404);
        }
    }

    public function getData(){
        $date = date('d-m-y');
        $hadir = Attendance::where('date', $date)->get()->count();
        $total = User::all()->count();
        $data = [
            "date" => $date,
            "hadir" => $hadir,
            "alfa" => $total - $hadir
        ];
        return response()->json(['data' => $data], 200);
    }

    public function getQR($email){
        $User = User::where('email', $email)->get();
        return response()->json(['data' => $User], 200);
    }
}
