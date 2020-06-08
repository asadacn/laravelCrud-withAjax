<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('welcome',compact('contacts'))->with('i');
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
            $rules= [
                'name'    => 'required|string',
                'contact' => 'required|numeric|unique:contacts',
                'email'   => 'email|nullable|unique:contacts',
                'address' =>'string|nullable'
            ];
            $validator = Validator::make($request->all(),$rules);
    
            // Validate the input and return correct response
            if ($validator->fails())
            {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->errors()->all()
    
                ), 400); // 400 being the HTTP code for an invalid request.
            }

            Contact::Create($request->input()); //Create if not exit or Update 

            return Response::json(array('success' => true), 200);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $key = $request->key;
        $i=0;
        $result='';
        $contacts = Contact::where('name', 'like', '%'.$key.'%')
                            ->orWhere('contact', 'like', '%'.$key.'%')
                            ->orWhere('email', 'like', '%'.$key.'%')
                            ->orWhere('address', 'like', '%'.$key.'%')->get();

                            foreach ($contacts as $contact){
                                $result .='
                            <tr>
                            <th scope="row">'.++$i.'</th>
                              <td>'.$contact->name.'</td>
                              <td>'.$contact->contact.'</td>
                              <td>'.$contact->email.'</td>
                              <td>'.$contact->address.'</td>
                              <td><div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-sm btn-light border-white" onclick="edit('.$contact->id.')"><img src="'.asset('edit.png').'" alt="EDIT" width="20px"> </button>
                                <button type="button" class="btn btn-sm btn-light border-white" onclick="del('.$contact->id.')"><img src="'.asset('delete.png').'" alt="DELETE" width="20px"> </button>
                              </div></td>
                            </tr>';
                             }

                             return response()->json([
                                 'html'=>$result,
                                 'count'=>$i
                             ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $contact = Contact::findOrFail($id);
        return response()->json([
            'contact' => $contact
        ]);
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
        $rules= [
            'name'    => 'required|string',
            'contact' => 'required|numeric|unique:contacts,contact,'.$id,
            'email'   => 'email|nullable|unique:contacts,email,'.$id,
            'address' =>'string|nullable'
        ];
        $validator = Validator::make($request->all(),$rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->errors()->all()

            ), 400); // 400 being the HTTP code for an invalid request.
        }
    $contact = Contact::find($request->id);
    $contact->name=$request->name;
    $contact->contact=$request->contact;
    $contact->email=$request->email;
    $contact->address=$request->address;
    $contact->save();

    return Response::json(array('success' => true), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::find($id)->delete();
     
        return response()->json(['success'=>'Contact deleted successfully.']);
    }
}
