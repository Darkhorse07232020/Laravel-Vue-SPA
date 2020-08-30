<?php

namespace App\Http\Controllers;

//Import Models
use App\Model\Style\Style_brand;
use App\Model\Style\Style_group;
use App\Model\Style\Style;
use App\Model\Style\Style_Material;
use App\Model\Style\Style_Color;
use App\Model\Style\Style_Finish;
use App\Model\Style\Style_Centerpanel;
use App\Model\Style\Style_Hardware;
use App\Model\Style\Style_Insideprofile;
use App\Model\Style\Style_Outsideprofile;
use App\Model\Style\Style_Stilerail;

use App\Model\Cabinet\Cabinet_Material;
use App\Model\Cabinet\Cabinet_Drwbox;
use App\Model\Cabinet\Cabinet_hinge;

use App\Model\Edge\Edge_banding;
use App\Model\Edge\Edge_wood;


//Import Libraries
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    //
    public function index() {
        $data = Style_brand::all();
        return response()->json($data, Response::HTTP_OK);
        
    }

    public function getStyle(Request $request) {
        $cond = $request -> condition;
        $data1 = Style_group::where('BrandID', $cond)
            ->orderBy('Name')
            ->select("Name", "ID", "BrandID")
            ->get()->toArray();
        
        $data2 = Cabinet_Material::where('BrandID', $cond)
            ->orderBy('Name')
            ->select("Name", "ID", "BrandID")
            ->get()->toArray();

        $data3 = Cabinet_Drwbox::where('BrandID', $cond)
            ->orderBy('Name')
            ->select("Name", "ID", "BrandID")
            ->get()->toArray();
        
        $data=['styles' => $data1, 'cmaterial' => $data2, 'dbox' => $data3];
        return response()->json($data, Response::HTTP_OK);


    }

    public function getDoor(Request $request) {
        $brand_id = $request -> BrandID;
        $group_id = $request -> GroupID;
        $data = Style::where('BrandID', $brand_id)
            ->where('GroupID', $group_id)
            ->where('StyleType', 'Door')
            ->orderBy('Name')
            ->get()->toArray();

        return response()->json($data, Response::HTTP_OK);
    }

    public function getMaterial(Request $request) {
        $material_str = $request -> MaterialID;
        $drawerstyle_str = $request -> DrawerStyleID;
        $lgdrawerstyle_str = $request -> LgDrawerStyleID;
        
        $materials = explode(",", $material_str);
        $drawerstyle = explode(",", $drawerstyle_str);
        $lgdrawerstyle = explode(",", $lgdrawerstyle_str);
        
        $data1 = array();
        $data2 = array();
        $data3 = array();

        foreach ($drawerstyle as $cond) {
            $tmp = Style::where('ID', $cond)
                ->select("Name", "ID")
                ->first()->toArray();
            array_push($data1, $tmp);
        }

        foreach ($lgdrawerstyle as $cond) {
            $tmp = Style::where('ID', $cond)
                ->select("Name", "ID")
                ->first()->toArray();
            array_push($data2, $tmp);
        }

        foreach ($materials as $cond) {
            $tmp = Style_Material::where('ID', $cond)
                ->select("Name", "ID", "ColorID")
                ->first()->toArray();
            array_push($data3, $tmp);
        }

        $data=['Drawer' => $data1, 'LgDrawer' => $data2, 'Material' => $data3 ];
        return response()->json($data, Response::HTTP_OK);
    }

    public function getColor(Request $request) {
        $color_str = $request -> ColorID;
        
        $colors = explode(",", $color_str);
        
        $data = array();

        foreach ($colors as $cond) {
            $tmp = Style_Color::where('ID', $cond)
                ->select("Name", "ID", "FinishID", "image")
                ->first()->toArray();
            array_push($data, $tmp);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    public function getFinish(Request $request) {
        $finish_str = $request -> FinishID;
        
        $finish = explode(",", $finish_str);
        
        $data = array();
        foreach ($finish as $cond) {
            $tmp = Style_Finish::where('ID', $cond)
                ->select("Name", "ID")
                ->first()->toArray();
            array_push($data, $tmp);
        }
        
        return response()->json($data, Response::HTTP_OK);
    }

    public function getEdge(Request $request) {
        $material_id = $request -> MaterialID;
        $color_id = $request -> ColorID;
        
        $data = Edge_banding::join('edge_banding_wood', 'edge_banding.ID', '=', 'edge_banding_wood.EdgeBandingID')
            ->where('edge_banding_wood.MaterialID', $material_id)
            ->where('edge_banding_wood.ColorID', $color_id)
            ->select('Name', 'ID')
            ->get()->toArray();

        $hinges = Cabinet_hinge::find(1)->get()->toArray();
        return response()->json(['edges' => $data, 'hinges' => $hinges], Response::HTTP_OK);
    }

    public function getProfile(Request $request) {
        $finish_str = $request -> FinishID;
        
        $finish = explode(",", $finish_str);
        
        $data = array();
        foreach ($finish as $cond) {
            $tmp = Style_Finish::where('ID', $cond)
                ->select("Name", "ID")
                ->first()->toArray();
            array_push($data, $tmp);
        }
        
        return response()->json($data, Response::HTTP_OK);
    }
}
