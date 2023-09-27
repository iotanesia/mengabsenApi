<!DOCTYPE html>
<html lang="en">
<body>
    <table>
        <tr>
            <td colspan="15" style="font-family:Calibri;font-size:11px;text-align:center;"><b>REKAP ABSEN</b></td>
        </tr>
    </table>
    <table style="border: 1px solid black; border-collapse: collapse;">
        <tr>
            <td style="text-align: center;vertical-align: middle;width: 50px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">No</td>
            <td style="text-align: center;vertical-align: middle;width: 120px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Nama</td>
            <td style="text-align: center;vertical-align: middle;width: 200px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Different Time</td>
            <td style="text-align: center;vertical-align: middle;width: 50px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Late</td>
            <td style="text-align: center;vertical-align: middle;width: 150px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Check In</td>
            <td style="text-align: center;vertical-align: middle;width: 150px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Check Out</td>
            <td style="text-align: center;vertical-align: middle;width: 70px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Type</td>
            <td style="text-align: center;vertical-align: middle;width: 350px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange;word-wrap:break-word;">Alamat In</td>
            <td style="text-align: center;vertical-align: middle;width: 350px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange;word-wrap:break-word;">Alamat Out</td>
            <td style="text-align: center;vertical-align: middle;width: 200px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Desc In</td>
            <td style="text-align: center;vertical-align: middle;width: 200px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;font-weight:bold;background:orange">Desc Out</td>
        </tr>
        @foreach($data as $key => $val)
        <tr>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$key + 1}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->name ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->diff_time ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->is_late ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->check_in ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->check_out ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->type ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;width: 350px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;word-wrap:break-word;">{{$val->address_in ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;width: 350px;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;word-wrap:break-word;">{{$val->address_out ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->desc_in ?? '-'}}</td>
            <td style="text-align: center;vertical-align: middle;font-family:Calibri;font-size:11px;text-align:left; border: 1px solid black;">{{$val->desc_out ?? '-'}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>