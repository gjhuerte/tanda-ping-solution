<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon;
use DB;

class PingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData($deviceid,$datefrom,$dateto=null)
    {

        $datefrom = $this->setDateFrom($datefrom);
        $dateto = $this->setDateTo($dateto);

        if($deviceid == 'devices')
        {
            return json_encode(App\Device::pluck('id'));
        }

        $epochtime = new App\EpochTime;


        if($datefrom != null)
        {

            if($dateto == null)
            {
                $dateto = Carbon\Carbon::createFromTimestamp($datefrom)->addDay()->timestamp;
            }
                    
            $epochtime = $epochtime->where(function($query) use($datefrom,$dateto) {
                $query->where('ping', '>=' , $datefrom)
                ->where('ping','<',$dateto);
            });
        }

        if($deviceid == 'all')
        {
            // $epochtime = $epochtime->pluck('ping') ;
        }
        else
        {

            $epochtime = $epochtime->where('device_id','=',$deviceid);

            // $epochtime = $epochtime->get(['ping','device_id'])
            //                         ->groupBy('device_id') ;
        }

        $epochtime = $epochtime->pluck('ping') ;

        return $epochtime;

        return json_encode($epochtime);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$deviceid,$ping)
    {

        $device = App\Device::find($deviceid);

        if(count($device) == 0)
        {
            $device = App\Device::create([
                'id' => $deviceid
            ]);
        }

        $device->epochtime()->create([ 'device_id' => $device->id , 'ping' => $ping ]);

        return response('success',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\EpochTime::truncate();
        App\Device::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response('success',200);
    }

    function isValidTimeStamp($strTimestamp) {
        return ((string) (int) $strTimestamp === (string) $strTimestamp) && ($strTimestamp = ~PHP_INT_MAX);
    }

    function setDateFrom($datefrom)
    {

        if($datefrom == null || $datefrom == '' || !isset($datefrom))
        {
            $datefrom = null;
        }
        else
        {
            if($this->isValidTimeStamp($datefrom))
            {
                $datefrom = Carbon\Carbon::createFromTimestamp($datefrom)->timestamp;
            }
            else
            {
                $datefrom = Carbon\Carbon::parse($datefrom)->timestamp;
            }
        }

        return $datefrom;

    }

    function setDateTo($dateto)
    {

        if($dateto == null || $dateto == '' || !isset($dateto))
        {
            $dateto = null;
        }
        else
        {
            if($this->isValidTimeStamp($dateto))
            {
                $dateto = Carbon\Carbon::createFromTimestamp($dateto)->timestamp;
            }
            else
            {
                $dateto = Carbon\Carbon::parse($dateto)->timestamp;
            }
        }

        return $dateto;
    }
}
