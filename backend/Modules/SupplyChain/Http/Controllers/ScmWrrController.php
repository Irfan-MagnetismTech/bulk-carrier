<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Modules\SupplyChain\Entities\ScmWrr;
use Modules\SupplyChain\Entities\ScmWoLine;
use Modules\SupplyChain\Entities\ScmWrLine;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWrrItem;
use Modules\SupplyChain\Entities\ScmWrrLine;
use Modules\SupplyChain\Http\Requests\ScmWrrRequest;

class ScmWrrController extends Controller
{
    // use HasRoles;

    function __construct(private FileUploadService $fileUpload)
    {
        //     $this->middleware('permission:work-receipt-report-create|work-receipt-report-edit|work-receipt-report-show|work-receipt-report-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:work-receipt-report-create', ['only' => ['store']]);
        //     $this->middleware('permission:work-receipt-report-edit', ['only' => ['update']]);
        //     $this->middleware('permission:work-receipt-report-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): JsonResponse
    {
        try {
            $wrrs = ScmWrr::query()
                ->with('scmWrrLines', 'scmWrrLineItems', 'scmWo', 'scmWarehouse', 'createdBy')
                ->globalSearch(request()->all());

            return response()->success('Data list', $wrrs, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmWrrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');

        try {
            DB::beginTransaction();

            $scmWrr = ScmWrr::create($requestData);
            $this->createScmWrrLinesAndItems($request, $scmWrr);

            DB::commit();
            return response()->success('Data created succesfully', $scmWrr, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    private function createScmWrrLinesAndItems($request, $scmWrr)
    {
        foreach ($request->scmWrrLines as $key => $values) {            
            $scmWrrLine = $scmWrr->scmWrrLines()->create([
                'scm_wr_id' => $values['scm_wr_id'],
            ]);

            foreach ($values['scmWrrLineItems'] as $index => $value) {
                ScmWrrItem::create([
                    'scm_service_id' => $values['scmWrrLineItems'][$index]['scm_service_id'],
                    'scm_wrr_line_id' => $scmWrrLine->id,
                    'scm_wrr_id' => $scmWrr->id,
                    'quantity' => $values['scmWrrLineItems'][$index]['quantity'],
                    'rate' => $values['scmWrrLineItems'][$index]['rate'],
                    'wo_composite_key' => $values['scmWrrLineItems'][$index]['wo_composite_key'],
                    'wr_composite_key' => $values['scmWrrLineItems'][$index]['wr_composite_key'],
                    'net_rate' => $values['scmWrrLineItems'][$index]['net_rate'],
                ]);
            }
        }
    }

    /**
     * Show the specified resource.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function show(ScmWrr $work_receipt_report): JsonResponse
    {       
        $work_receipt_report->load('scmWrrLines.scmWrrLineItems.scmService', "scmWrrLines.scmWr", 'scmWarehouse', 'scmWrrLineItems', 'createdBy', 'scmWrrLines.scmWrrLineItems.scmWoItem.scmService', 'scmWo');
        $scmWrrLines = $work_receipt_report->scmWrrLines->map(function ($items) {
            $datas = $items;
            $adas = $items->scmWrrLineItems->map(function ($item) {
                    $max_quantity = ($item?->scmWoItem?->quantity ?? 0) -  ($item?->scmWoItem?->scmWrrLineItems?->sum('quantity')  ?? 0) + $item->quantity;
                        return [
                            'id' => $item['id'],
                            'scm_service_id' => $item['scm_service_id'],
                            'scmService' => $item['scmService'],
                            'quantity' => $item['quantity'],
                            'rate' => $item['rate'],
                            'total_price' => $item['rate'] * $item['quantity'],
                            'net_rate' => $item['net_rate'],
                            'wo_composite_key' => $item['wo_composite_key'],
                            'wr_composite_key' => $item['wr_composite_key'],
                            'max_quantity' => $max_quantity,
                            'remaining_quantity' => $max_quantity,
                            'wr_qty' => $item?->scmWrLine?->quantity,
                            'wo_qty' => ($item?->scmWoItem?->quantity ?? 0),

                        ];
            });
            data_forget($items, 'scmWrrLineItems');
            $datas['scmWrrLineItems'] = $adas;
            return $datas;
        });

        data_forget($work_receipt_report, 'scmWrrLines');
        $work_receipt_report['scmWrrLines'] = $scmWrrLines;

        try {
            return response()->success('data', $work_receipt_report, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmWrrRequest $request
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function update(ScmWrrRequest $request, ScmWrr $work_receipt_report): JsonResponse
    {
        $work_receipt_report->load('scmWrrLines','scmWrrLineItems');
        try {
            DB::beginTransaction();

            $work_receipt_report->update($request->all());
            $work_receipt_report->scmWrrLineItems()->delete();
            $work_receipt_report->scmWrrLines()->delete();

            $this->createScmWrrLinesAndItems($request, $work_receipt_report);

            DB::commit();

            return response()->success('Data updated sucessfully!', $work_receipt_report, 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function destroy(ScmWrr $work_receipt_report): JsonResponse
    {
        $work_receipt_report->load('scmWrrLines','scmWrrLineItems');
        try {
            DB::beginTransaction();
            $work_receipt_report->scmWrrLineItems()->delete();
            $work_receipt_report->scmWrrLines()->delete();
            $work_receipt_report->delete();
            DB::commit();
            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($work_receipt_report->preventDeletionIfRelated(), 422);
        }
    }


    public function searchWrr(Request $request): JsonResponse
    {
        if ($request->has('searchParam')) {
            $serviceReceiptReport = ScmWrr::query()
                ->with('scmWrrLineItems.scmService')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        } else {
            $serviceReceiptReport = ScmWrr::query()
                ->with('scmWrrLineItems.scmService')
                ->where(function ($query) use ($request) {
                    $query->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        }

        $serviceReceiptReport = $serviceReceiptReport->map(function ($item) {
            $item['scmServices'] = $item->scmWrrLineItems->map(function ($item1) {
                $data = $item1->scmService;
                $data['purchase_price'] = $item1->rate;
                return $data;
            });
            return $item;
        });

        return response()->success('Search Result', $serviceReceiptReport, 200);
    }


        /**
     * Retrieves the materials associated with a given purchase requisition ID.
     *
     * @return JsonResponse
     */
    public function getServiceByWrrId(): JsonResponse
    {
        if (!request()->scm_wo_id) {
            $wrServices = ScmWrLine::query()
                ->with('scmService', 'scmWrrLines')
                ->where('scm_wr_id', request()->scm_wr_id)
                ->get()
                ->map(function ($item) {
                    $data = $item->scmService;
                    $data['quantity'] = $item->quantity;
                    $data['wr_qty'] = $item->quantity;
                    $data['wo_qty'] = 0;
                    $data['rate'] = 0;
                    $data['net_rate'] = 0;
                    $data['wr_composite_key'] = $item->wr_composite_key;
                    $data['wo_composite_key'] = null;
                    if (request()->scm_wrr_id) {
                        $data['wrr_quantity'] = $item->scmWrrLines->where('scm_wrr_id', request()->scm_wrr_id)->where('wr_composite_key', $item->wr_composite_key)->first()?->quantity ?? 0;
                    } else {
                        $data['wrr_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmWrrLines?->sum('quantity') + $data['wrr_quantity'];

                    return $data;
                });

            return response()->success('data list', $wrServices, 200);
        }

        if (request()->scm_wo_id) {
            $wrServices = ScmWoLine::query()
                ->with('scmService', 'scmWrrLines', 'scmWrLine')
                ->where('scm_wo_id', request()->scm_wo_id)
                ->get()
                ->map(function ($item) {
                    $data = $item->scmService;
                    $data['quantity'] = $item->quantity;
                    if (request()->scm_wrr_id) {
                        $data['wrr_quantity'] = $item->scmWrrLines->where('scm_wrr_id', request()->scm_wrr_id)->where('wo_composite_key', $item->wo_composite_key)->first()?->quantity ?? 0;
                    } else {
                        $data['wrr_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmWrrLines?->sum('quantity') + $data['wrr_quantity'];
                    $data['wo_qty'] = $item->quantity;
                    $data['wr_qty'] = $item->scmWrLine?->quantity;
                    $data['rate'] = $item->rate;
                    $data['net_rate'] = $item->net_rate;
                    $data['wr_composite_key'] = $item->wr_composite_key;
                    $data['wo_composite_key'] = $item->wo_composite_key;
                    // $data['max_quantity'] = $item->quantity - $item->scmMrrLines->sum('quantity');//some edit needed
                    return $data;
                });

            return response()->success('data list', $wrServices, 200);
        }
    }

    public function getWrrLineData(): JsonResponse
    {
        if (request()->scm_wo_id && request()->scm_wr_id) {
            $scmWo = ScmWoLine::query()
                ->with('scmWo', 'scmWoItems.scmService', 'scmWr', 'scmWoItems.scmWrrLineItems', 'scmWoItems.scmWrLine')
                ->where('scm_wo_id', request()->scm_wo_id)
                ->where('scm_wr_id', request()->scm_wr_id)
                ->first();

            $data = $scmWo->scmWoItems->map(function ($item) {
                if (request()->scm_wrr_id) {
                    $wrrQuantity = $item->scmWrrLineItems->where('scm_wrr_id', request()->scm_wrr_id)->where('wo_composite_key', $item->wo_composite_key)->first()?->quantity ?? 0;
                } else {
                    $wrrQuantity = 0;
                }
                $totalWrrQuantity = $item?->scmWrrLineItems?->sum('quantity');

                $remainingQuantity = $item->quantity - $totalWrrQuantity + $wrrQuantity;
                return [
                    'scmService' => $item->scmService,
                    'scm_service_id' => $item->scm_service_id,
                    'quantity' => $remainingQuantity,
                    'rate' => $item->rate,
                    'net_rate' => $item->net_rate,
                    'wo_qty' => $item->quantity,
                    'wr_qty' => $item->scmWrLine->quantity,
                    'wo_composite_key' => $item->wo_composite_key,
                    'wr_composite_key' => $item->wr_composite_key,
                    'wrr_quantity' => $remainingQuantity,
                    'remaining_quantity' => $remainingQuantity,
                    'max_quantity' => $remainingQuantity,
                ];
            });
        } else {
            $data = [];
        }
        return response()->success('data', $data, 200);
    }

    public function getWoServiceList(): JsonResponse
    {
        $scmWo = ScmWoLine::query()
            ->with('scmWo', 'scmWoItems.scmService', 'scmWr', 'scmWoItems.scmWrrLineItems', 'scmWoItems.scmWrLine')
            ->where('scm_wo_id', request()->scm_wo_id)
            ->where('scm_wr_id', request()->scm_wr_id)
            ->first();

        $data = $scmWo->scmWoItems->map(function ($item) {
            if (request()->scm_wrr_id) {
                $wrrQuantity = $item->scmWrrLineItems->where('scm_wrr_id', request()->scm_wrr_id)->where('wo_composite_key', $item->wo_composite_key)->first()?->quantity ?? 0;
            } else {
                $wrrQuantity = 0;
            }

            $totalWrrQuantity = $item?->scmMrrLineItems?->sum('quantity') ?? 0;

            $remainingQuantity = $item->quantity - $totalWrrQuantity + $wrrQuantity;

            $data = $item->scmService;
            $data['quantity'] = $remainingQuantity;
            $data['rate'] = $item->rate;
            $data['net_rate'] = $item->net_rate;
            $data['wo_qty'] = $item->quantity;
            $data['wr_qty'] = $item->scmWrLine->quantity;
            $data['wo_composite_key'] = $item->wo_composite_key;
            $data['wr_composite_key'] = $item->wr_composite_key;
            $data['wrr_quantity'] = $remainingQuantity;
            $data['remaining_quantity'] = $remainingQuantity;
            $data['max_quantity'] = $remainingQuantity;
            return $data;
        });

        return response()->success('data', $data, 200);
    }
}
