<?php

namespace App\Http\Controllers;

use App\Models\TaxInternationalInvoiceReport;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaxInternationalInvoiceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            $query = TaxInternationalInvoiceReport::query();
            
            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_no', 'like', '%' . $search . '%')
                      ->orWhere('lic_no', 'like', '%' . $search . '%')
                      ->orWhere('pan_no', 'like', '%' . $search . '%')
                      ->orWhere('particular', 'like', '%' . $search . '%');
                });
            }
            
            // Filter by client_id if provided
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('invoice_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }
            
            $query->orderBy($sortBy, $sortOrder);
            
            $invoices = $query->paginate($perPage);
            $users    = User::pluck('name','id');
            return view('tax-international-invoice-reports.index', compact('invoices', 'search', 'sortBy', 'sortOrder','users'));
            
        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to retrieve invoices: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clients = Client::get();
            return view('tax-international-invoice-reports.create', compact('clients'));
            // return view('tax-international-invoice-reports.create');
            
        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOld(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'nullable|integer',
                'invoice_no' => 'required|string|max:100|unique:invoice_report,invoice_no',
                'invoice_date' => 'required|date',
                'lic_no' => 'nullable|string|max:100',
                'pan_no' => 'nullable|string|max:50',
                'particular' => 'required|string',
                'nos' => 'required|integer|min:1',
                'unit_rate' => 'required|numeric|min:0',
                'cgst' => 'nullable|numeric|min:0|max:100',
                'sgst' => 'nullable|numeric|min:0|max:100',
            ], [
                'invoice_no.unique' => 'This invoice number already exists',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $invoiceNo = $request->invoice_no;
            $numericInvoiceNo = (int) preg_replace('/[^0-9]/', '', $invoiceNo);
            
            if ($numericInvoiceNo == 0) {
                $numericInvoiceNo = rand(100000, 999999);
            }
            
            $existing = TaxInternationalInvoiceReport::where('invoice_no', $numericInvoiceNo)->exists();
            if ($existing) {
                $numericInvoiceNo = $numericInvoiceNo . rand(10, 99);
            }
            
            $invoiceDate = $request->invoice_date;
            
            $licNo = $request->lic_no;
            $numericLicNo = null;
            if ($licNo) {
                $numericLicNo = (int) preg_replace('/[^0-9]/', '', $licNo);
                if ($numericLicNo == 0) {
                    $numericLicNo = null;
                }
            }
            
            $panNo = $request->pan_no;
            $numericPanNo = null;
            if ($panNo) {
                $numericPanNo = (int) preg_replace('/[^0-9]/', '', $panNo);
                if ($numericPanNo == 0) {
                    $numericPanNo = null;
                }
            }
            
            $nos = intval($request->nos);
            $unitRate = floatval($request->unit_rate);
            $cgstPercent = floatval($request->cgst ?? 0);
            $sgstPercent = floatval($request->sgst ?? 0);
            
            $totalAmount = intval(round($nos * $unitRate, 0));
            
            $cgstAmount = $totalAmount * ($cgstPercent / 100);
            $sgstAmount = $totalAmount * ($sgstPercent / 100);
            $totalGST = $cgstAmount + $sgstAmount;
            
            $netAmount = intval(round($totalAmount + $totalGST, 0));
            
            $invoiceData = [
                'client_id' => intval($request->client_id) ?: null,
                'invoice_no' => $numericInvoiceNo, 
                'invoice_date' => $invoiceDate,
                'lic_no' => $numericLicNo,
                'pan_no' => $numericPanNo,
                'particular' => $request->particular,
                'nos' => $nos, 
                'unit_rate' => intval(round($unitRate, 0)),
                'total_amount' => $totalAmount,
                'net_amount' => $netAmount,
                'cgst' => intval(round($cgstPercent, 0)),
                'sgst' => intval(round($sgstPercent, 0)),
                'created_by' => Auth::id(),
                'tan' => $request->tan,
            ];

            $invoice = TaxInternationalInvoiceReport::create($invoiceData);
            
            DB::commit();
            
            session()->flash('original_invoice_no', $request->invoice_no);
            
            return redirect()->route('tax-international-invoice-reports.show', $invoice->id)->with('success', 'Invoice #' . $request->invoice_no . ' created successfully! (Saved as #' . $numericInvoiceNo . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice Store Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            // Return with error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'nullable|integer',
                'invoice_no' => 'required|string|max:100|unique:invoice_report,invoice_no',
                'invoice_date' => 'required|date',
                'lic_no' => 'nullable|string|max:100',
                'pan_no' => 'nullable|string|max:50',
                'particular' => 'required|string',
                'nos' => 'required|integer|min:1',
                'unit_rate' => 'required|numeric|min:0',
            ], [
                'invoice_no.unique' => 'This invoice number already exists',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $invoiceNo = $request->invoice_no;
            $numericInvoiceNo = (int) preg_replace('/[^0-9]/', '', $invoiceNo);
            
            if ($numericInvoiceNo == 0) {
                $numericInvoiceNo = rand(100000, 999999);
            }
            
            $existing = TaxInternationalInvoiceReport::where('invoice_no', $numericInvoiceNo)->exists();
            if ($existing) {
                $numericInvoiceNo = $numericInvoiceNo . rand(10, 99);
            }
            
            $invoiceDate = $request->invoice_date;
            
            $licNo = $request->lic_no;
            $numericLicNo = null;
            if ($licNo) {
                $numericLicNo = (int) preg_replace('/[^0-9]/', '', $licNo);
                if ($numericLicNo == 0) {
                    $numericLicNo = null;
                }
            }
            
            $panNo = $request->pan_no;
            $numericPanNo = null;
            if ($panNo) {
                $numericPanNo = (int) preg_replace('/[^0-9]/', '', $panNo);
                if ($numericPanNo == 0) {
                    $numericPanNo = null;
                }
            }
            
            $nos = intval($request->nos);
            $unitRate = floatval($request->unit_rate);
            $totalAmount = intval(round($nos * $unitRate, 0));
            
            $invoiceData = [
                'client_id' => intval($request->client_id) ?: null,
                'invoice_no' => $numericInvoiceNo, 
                'invoice_date' => $invoiceDate,
                'lic_no' => $numericLicNo,
                'pan_no' => $numericPanNo,
                'particular' => $request->particular,
                'nos' => $nos, 
                'unit_rate' => intval(round($unitRate, 0)),
                'total_amount' => $totalAmount,
                'created_by' => Auth::id(),
                'tan' => $request->tan,
            ];

            $invoice = TaxInternationalInvoiceReport::create($invoiceData);
            
            DB::commit();
            
            session()->flash('original_invoice_no', $request->invoice_no);
            
            return redirect()->route('tax-international-invoice-reports.show', $invoice->id)->with('success', 'Invoice #' . $request->invoice_no . ' created successfully! (Saved as #' . $numericInvoiceNo . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice Store Error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            // Return with error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::find($id);
            
            if (!$invoice) {
                return redirect()->route('tax-international-invoice-reports.index')
                    ->with('error', 'Invoice not found');
            }
            $clients = Client::find($invoice->client_id);
            // $invoice->load('client', 'creator');

            return view('tax-international-invoice-reports.show', compact('invoice','clients'));

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to retrieve invoice: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::find($id);
            
            if (!$invoice) {
                return redirect()->route('tax-international-invoice-reports.index')
                    ->with('error', 'Invoice not found');
            }

            // You can pass any necessary data to the view
            // For example: $clients = Client::all();
            // return view('tax-international-invoice-reports.edit', compact('invoice', 'clients'));
            
            return view('tax-international-invoice-reports.edit', compact('invoice'));

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::find($id);
            
            if (!$invoice) {
                return redirect()->route('tax-international-invoice-reports.index')
                    ->with('error', 'Invoice not found');
            }

            $validator = Validator::make($request->all(), [
                'client_id' => 'required|integer',
                'invoice_no' => 'required|string|max:100|unique:invoice_report,invoice_no,' . $id,
                'invoice_date' => 'required|date',
                'lic_no' => 'nullable|string|max:100',
                'pan_no' => 'nullable|string|max:50',
                'particular' => 'required|string',
                'nos' => 'required|integer|min:1',
                'unit_rate' => 'required|numeric|min:0',
                'total_amount' => 'nullable|numeric|min:0',
                'net_amount' => 'nullable|numeric|min:0',
                'cgst' => 'nullable|numeric|min:0',
                'sgst' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Recalculate amounts if needed
            $data = $request->all();
            
            // Calculate total amount if not provided or if nos/unit_rate changed
            if (!isset($data['total_amount']) || $data['total_amount'] == 0 || 
                $data['nos'] != $invoice->nos || $data['unit_rate'] != $invoice->unit_rate) {
                $data['total_amount'] = $data['nos'] * $data['unit_rate'];
            }
            
            // Calculate net amount if not provided or if GST or total amount changed
            if (!isset($data['net_amount']) || $data['net_amount'] == 0 ||
                $data['total_amount'] != $invoice->total_amount ||
                ($data['cgst'] ?? 0) != $invoice->cgst ||
                ($data['sgst'] ?? 0) != $invoice->sgst) {
                $gst = ($data['cgst'] ?? 0) + ($data['sgst'] ?? 0);
                $data['net_amount'] = $data['total_amount'] + ($data['total_amount'] * $gst / 100);
            }

            $invoice->update($data);

            return redirect()->route('tax-international-invoice-reports.show', $invoice->id)
                ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::find($id);
            
            if (!$invoice) {
                return redirect()->route('tax-international-invoice-reports.index')
                    ->with('error', 'Invoice not found');
            }

            $invoice->delete();

            return redirect()->route('tax-international-invoice-reports.index')
                ->with('success', 'Invoice deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display invoice statistics
     */
    public function statistics(Request $request)
    {
        try {
            $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

            $stats = TaxInternationalInvoiceReport::whereBetween('invoice_date', [$startDate, $endDate])
                ->select([
                    DB::raw('COUNT(*) as total_invoices'),
                    DB::raw('SUM(net_amount) as total_revenue'),
                    DB::raw('AVG(net_amount) as average_invoice_value'),
                    DB::raw('SUM(cgst) as total_cgst'),
                    DB::raw('SUM(sgst) as total_sgst'),
                ])
                ->first();

            // Monthly breakdown
            $monthlyBreakdown = TaxInternationalInvoiceReport::whereBetween('invoice_date', [$startDate, $endDate])
                ->select([
                    DB::raw('YEAR(invoice_date) as year'),
                    DB::raw('MONTH(invoice_date) as month'),
                    DB::raw('DATE_FORMAT(invoice_date, "%M %Y") as month_name'),
                    DB::raw('COUNT(*) as invoice_count'),
                    DB::raw('SUM(net_amount) as total_amount')
                ])
                ->groupBy('year', 'month', 'month_name')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

            return view('tax-international-invoice-reports.statistics', compact('stats', 'monthlyBreakdown', 'startDate', 'endDate'));

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to retrieve statistics: ' . $e->getMessage());
        }
    }

    /**
     * Export invoices to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = TaxInternationalInvoiceReport::query();
            
            // Apply filters if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('invoice_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }
            
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            $invoices = $query->orderBy('invoice_date', 'desc')->get();
            
            $filename = 'invoices_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($invoices) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // Add CSV headers
                fputcsv($file, [
                    'Invoice No',
                    'Date',
                    'Client ID',
                    'LIC No',
                    'PAN No',
                    'Particular',
                    'Quantity',
                    'Unit Rate',
                    'Total Amount',
                    'CGST',
                    'SGST',
                    'Net Amount',
                    'Created At'
                ]);

                // Add data rows
                foreach ($invoices as $invoice) {
                    fputcsv($file, [
                        $invoice->invoice_no,
                        $invoice->invoice_date,
                        $invoice->client_id,
                        $invoice->lic_no,
                        $invoice->pan_no,
                        $invoice->particular,
                        $invoice->nos,
                        $invoice->unit_rate,
                        $invoice->total_amount,
                        $invoice->cgst,
                        $invoice->sgst,
                        $invoice->net_amount,
                        $invoice->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.index')
                ->with('error', 'Failed to export invoices: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice
     */
    public function print($id)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::find($id);
            
            if (!$invoice) {
                return redirect()->route('tax-international-invoice-reports.index')
                    ->with('error', 'Invoice not found');
            }

            return view('tax-international-invoice-reports.print', compact('invoice'));

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.show', $id)
                ->with('error', 'Failed to generate print view: ' . $e->getMessage());
        }
    }

    /**
     * Show search form
     */
    public function searchForm()
    {
        return view('tax-international-invoice-reports.search');
    }

    /**
     * Process search
     */
    public function search(Request $request)
    {
        try {
            $query = TaxInternationalInvoiceReport::query();

            if ($request->filled('invoice_no')) {
                $query->where('invoice_no', 'like', '%' . $request->invoice_no . '%');
            }

            if ($request->filled('particular')) {
                $query->where('particular', 'like', '%' . $request->particular . '%');
            }

            if ($request->filled('lic_no')) {
                $query->where('lic_no', 'like', '%' . $request->lic_no . '%');
            }

            if ($request->filled('pan_no')) {
                $query->where('pan_no', 'like', '%' . $request->pan_no . '%');
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('invoice_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            $invoices = $query->orderBy('invoice_date', 'desc')->paginate(20);

            return view('tax-international-invoice-reports.search-results', compact('invoices'));

        } catch (\Exception $e) {
            return redirect()->route('tax-international-invoice-reports.search')
                ->with('error', 'Search failed: ' . $e->getMessage());
        }
    }

    /**
     * Get invoice by invoice number
     */
    public function getByInvoiceNumber(Request $request)
    {
        try {
            $invoice = TaxInternationalInvoiceReport::where('invoice_no', $request->invoice_no)->first();
            
            if (!$invoice) {
                return redirect()->back()
                    ->with('error', 'Invoice not found with number: ' . $request->invoice_no);
            }

            return redirect()->route('tax-international-invoice-reports.show', $invoice->id);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to find invoice: ' . $e->getMessage());
        }
    }
}