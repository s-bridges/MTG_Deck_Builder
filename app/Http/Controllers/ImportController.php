<?php
namespace App\Http\Controllers;
// whatever your deck model is below
use App\Deck;
use App\CsvData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Excel;
class ImportController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function getImport()
    {
        // gets your original page to show upload button
        return view('import.import');
    }
    // default param is set to deck for deck
    protected function getModel($type = 'Deck')
    {
        // switch case to return model so that this can be reused dynamically based on the select options in your import blade file
        // This really doesn't need to be used as a switch case unless you want to make it dynamic
        switch ($type) {
            case 'Deck':
            return new Deck;
            break;
        }
    }
    protected $validate_fields = [
        // this is where all of the rules are applied for the type of field each need to be for the database
        // you'll add more in here depending on what model you are importing with dynamically, make sure your array keys match your columns in the db table
        'Subaccount' => [
            'card_id'     =>  'required|string',
            'card_text'   =>  'required|string',
            'mana_cost'       =>  'required|string'
        ]
    ];
    public function saveImport()
    {
        // get the path of the uploaded file
        $path = $this->request->file('csv_file')->getRealPath();
        // map the file
        $data = array_map('str_getcsv', file($path));
        // create the record of the import in the database (not the actual data, just a reference to it into the csv table)
        $csv_data_file = CsvData::create([
            'csv_filename' => $this->request->file('csv_file')->getClientOriginalName(),
            'csv_header' => $this->request->has('header'),
            'csv_data' => json_encode($data),
            'object_type' => $this->request->input('type'),
            'client_id' => Auth::user()->client->id
        ]);
        // if successfully added to the DB
        if ($csv_data_file) {
            // redirect to page with csv id as parameter
            return redirect()->to('/setup/importing/' . $csv_data_file->id);
        } else {
            return redirect()->back();
        }
    }
    public function importing($id)
    {
        // get the csv record from the DB
        $data = CsvData::where('id', $id)->where('client_id', Auth::user()->client->id)->get();
        // came to us as an array but we know there is only one record
        $data = $data[0];
        // decode the data that was store in the db
        $csv_data = json_decode($data->csv_data);
        // this gets the model dynamically, again you could technically write this as $model = new Deck;
        $model = $this->getModel($data->object_type);
        // gets the possible importable fields (helps with data integrity which is set in the actual Deck Model)
        $importable_fields = $model['importable'];
        // pass in the importable fields, csv, etc 
        return view('import.import_fields', compact('csv_data', 'data', 'importable_fields'));
    }
    public function process()
    {
        // this is what actually adds the data into the database for Deck
        $csv_id = $this->request->input('csv_data_file_id');
        $data = CsvData::where('id', $csv_id)->where('client_id', Auth::user()->client->id)->first();
        $csv_data = json_decode($data->csv_data);
        // list of items to be inserted into database after
        $items = []; 
        foreach ($csv_data as $row) {
            $dummy = [];
            // loop through each of the fields set on the columns "headers"
            foreach ($this->request->fields as $index => $field) {
                // create a dummy array we can validate, then do mass create
                $dummy[$field] = $row[$index];
            }
            // validate the fields you are wanting to import into the DB
            $validator = Validator::make($dummy, $this->validate_fields[$data->object_type]);
            if ($validator->passes()){
                // push into items array
                $dummy['client_id'] = Auth::user()->client->id;
                $items[] = $dummy;
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        // get model to do mass insert into
        $model = $this->getModel($data->object_type);
        // Mass insert all items in one query
        $inserted = $model::insert($items);
        if ($inserted) {
            return view('import.import_success');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}