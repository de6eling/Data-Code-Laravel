<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use phpDocumentor\Reflection\Types\Object_;

class CodeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = DB::table('users')
            ->join('user_has_group', 'user_has_group.userId', '=', 'users.id')
            ->join('group', 'group.id', '=', 'user_has_group.groupId')
            ->select('group.id', 'group.key', 'group.name')
            ->where('user_has_group.userId', Auth::id())
            ->get();
        
        $files = [];

        foreach ($groups as $group) {

            $result = DB::table('file')
                ->select('*')
                ->where('groupId', $group->id)
                ->get();


            array_push($files, $result);


        }
        //dd($files);

        return view('code.manage', compact('groups', 'files'));
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
    public function store(Request $request)
    {
        //dd($request->all());
        if ($request->file('code')->isValid()) {
            $path = $request->code->store('files');
            $name = $request->code->getClientOriginalName();

            $ins = DB::table('file')
                ->insertGetId([
                    'name' => $name,
                    'path' => $path,
                    'groupId' => $request->groupId
                ]);

            //dd($ins);
            $file = Excel::load('storage/app/'.$path, function($reader) {})->toObject();

            foreach ($file as $sheet) {
                $sheetId = DB::table('sheet')
                    ->insertGetId([
                        'name' => $sheet->getTitle(),
                        'fileId' => $ins
                    ]);

                $variableIds = [];
                foreach ($sheet->first()->keys() as $variable) {
                    $varId = DB::table('variable')
                        ->insertGetId([
                            'name' => $variable
                        ]);

                    DB::table('sheet_has_variable')
                        ->insert([
                            'sheetId' => $sheetId,
                            'variableId' => $varId
                        ]);
                    array_push($variableIds, $varId);
                }

                //dd($variableIds);

                foreach ($sheet as $datapoint) {
                    $datapointId = DB::table('datapoint')
                        ->insertGetId([
                            'sheetId' => $sheetId
                        ]);
                    $i = 0;
                    foreach ($datapoint->toArray() as $key => $data) {
                        DB::table('datapoint_has_variable')
                            ->insert([
                                'datapointId' => $datapointId,
                                'variableId' => $variableIds[$i],
                                'value' => $data
                            ]);
                        $i++;
                    }
                }
            }

            return back()->with('success', 'Your file is uploaded and ready to code!');
        }

        return back()->with('error', 'Your file is invalid');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sheets = DB::table('sheet')
            ->select('id', 'name')
            ->where('fileId', $id)
            ->get();

        $variables = [];

        $data = [];

        $codes = [];

        $pointCodes = DB::table('datapoint_has_code')
            ->select('datapoint_has_code.*', 'code.name')
            ->join('code', 'code.id', '=', 'datapoint_has_code.codeId')
            ->where('userId', Auth::id())
            ->get();

        //dd($pointCodes);

        foreach ($sheets as $sheet) {
            $varSet = DB::table('variable')
                ->join('sheet_has_variable', 'sheet_has_variable.variableId', '=', 'variable.id')
                ->select('variable.name', 'variable.id')
                ->where('sheet_has_variable.sheetId', $sheet->id)
                ->get();

            array_push($variables, $varSet);

            $dataSet = DB::table('datapoint')
                ->join('datapoint_has_variable', 'datapoint_has_variable.datapointId', '=', 'datapoint.id')
                ->join('variable', 'variable.id', '=', 'datapoint_has_variable.variableId')
                ->select('variable.name', 'datapoint.id', 'datapoint_has_variable.value')
                ->where('datapoint.sheetId', $sheet->id)
                ->get();

            // My brilliant way to give a randomized set to every user.  It is a shuffle function that uses the users ID as the seed to produce the same random shuffle for each person.
            $dataSet = $dataSet->chunk(sizeof($varSet));

            $arr = $dataSet->all();

            $this->seededShuffle($arr, Auth::id());

            array_push($data, $arr);

            $sheetCodes = DB::table('code')
                ->select('id', 'name', 'description')
                ->where('sheetId', $sheet->id)
                ->get();

            array_push($codes, $sheetCodes);

        }

        //dd($data);
        return view('code.code', compact('sheets', 'variables', 'data', 'codes', 'pointCodes'));
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
    public function destroy($id)
    {
        //
    }


    private function seededShuffle(array &$array, $seed) {
        mt_srand($seed);
        $size = count($array);
        for ($i = 0; $i < $size; ++$i) {
            list($chunk) = array_splice($array, mt_rand(0, $size-1), 1);
            array_push($array, $chunk);
        }
    }

}
