<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParticipantSelectResource;
use App\Http\Resources\ParticipantResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ParticipantResource::collection(Participant::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->participant_validator();

        foreach ($request['novy_herec'] as $herec){
            Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
        }
        return response()->json(['ok'=> 'ok', 'message' => 'ok'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new ParticipantResource(Participant::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);
        $this->participant_validator();

        $participant->update([
            'name' => $request['jmeno'],
            'surname' => $request['prijmeni'],
            'birth' => $request['datum_narozeni'],
        ]);

        $participant->save();
        return response()->json(['ok'=> 'ok', 'message' => 'ok'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Participant  $participant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->title()->detach();
        $participant->delete();
        return response()->json(['ok'=> 'ok', 'message' => 'ok'], 200);
    }

    public function get_items_select()
    {
        return ParticipantSelectResource::collection(Participant::all());
    }

    protected function participant_validator(){
        return request()->validate([
            'novy_herec.*.jmeno' => 'required|string|max:255',
            'novy_herec.*.prijmeni' => 'required|string|max:255',
            'novy_herec.*.datum_narozeni' => 'required|date|before:today',
        ]);
    }
}
