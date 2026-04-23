@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 py-10">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

<div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

<div>
<p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
KASS CARE
</p>

<h1 class="text-3xl font-extrabold text-white">
Visit Clinical Note
</h1>

<p class="text-indigo-100 mt-2">
Add provider documentation for this visit.
</p>
</div>

<a href="{{ route('provider.notes.index') }}"
class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-indigo-700 shadow">
Back to Notes
</a>

</div>
</div>


<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

<div class="mb-6 space-y-2">
<p><strong>Visit ID:</strong> {{ $visit->id }}</p>
<p><strong>Client:</strong> {{ $visit->client->full_name ?? 'N/A' }}</p>
<p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
<p><strong>Date:</strong> {{ $visit->visit_date ?? 'N/A' }}</p>
</div>


<form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
@csrf

<input type="hidden" name="visit_id" value="{{ $visit->id }}">
<input type="hidden" name="note" id="finalNote">


<div class="bg-slate-50 rounded-xl border border-slate-200 p-6">

<h3 class="text-xl font-bold text-slate-900 mb-2">
Clinical Measurements
</h3>

<p class="text-sm text-slate-500 mb-6">
Enter height and weight to auto-calculate BMI for this provider note.
</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Weight (kg)
</label>

<input
type="number"
step="0.01"
id="weight"
name="weight"
value="{{ old('weight') }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="e.g. 70">
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Height (cm)
</label>

<input
type="number"
step="0.01"
id="height"
name="height"
value="{{ old('height') }}"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="e.g. 175">
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
BMI
</label>

<input
type="text"
id="bmi"
name="bmi"
readonly
class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-700"
placeholder="Auto-calculated">
</div>

</div>

<p id="bmiStatus" class="mt-4 text-xs font-semibold bg-gray-100 inline-flex rounded-full px-3 py-1">
BMI status will appear here
</p>

</div>


<div class="space-y-6">

<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Chief Complaint
</label>

<textarea
name="chief_complaint"
rows="3"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Patient complaint..."></textarea>
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Care Logs Summary
</label>

<textarea
name="care_logs"
rows="3"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Caregiver observations..."></textarea>
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Medical History
</label>

<textarea
name="medical_history"
rows="3"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Relevant history..."></textarea>
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Medications
</label>

<textarea
name="medications"
rows="3"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Current medications..."></textarea>
</div>

</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Objective
</label>

<textarea
name="objective"
rows="4"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Vitals, physical exam findings..."></textarea>
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Assessment
</label>

<textarea
name="assessment"
rows="4"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Clinical diagnosis or impression..."></textarea>
</div>


<div>
<label class="block text-sm font-semibold text-gray-700 mb-2">
Plan
</label>

<textarea
name="plan"
rows="4"
class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
placeholder="Treatment plan, orders, follow-up..."></textarea>
</div>


<button
type="submit"
class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold shadow hover:bg-indigo-700">
Save Note
</button>

</form>

</div>
</div>
</div>


<script>

function calculateBMI(){

const weight=document.getElementById('weight').value
const height=document.getElementById('height').value
const bmiInput=document.getElementById('bmi')
const status=document.getElementById('bmiStatus')

if(!weight||!height)return

const h=height/100
const bmi=(weight/(h*h)).toFixed(2)

bmiInput.value=bmi

let label=''

if(bmi<18.5) label='Underweight'
else if(bmi<25) label='Normal'
else if(bmi<30) label='Overweight'
else label='Obese'

status.innerHTML="BMI: "+bmi+" ("+label+")"

}

document.getElementById('weight').addEventListener('input',calculateBMI)
document.getElementById('height').addEventListener('input',calculateBMI)


document.querySelector('form').addEventListener('submit',function(){

const chief=document.querySelector('[name=chief_complaint]').value
const logs=document.querySelector('[name=care_logs]').value
const history=document.querySelector('[name=medical_history]').value
const meds=document.querySelector('[name=medications]').value
const objective=document.querySelector('[name=objective]').value
const assessment=document.querySelector('[name=assessment]').value
const plan=document.querySelector('[name=plan]').value

const weight=document.getElementById('weight').value
const height=document.getElementById('height').value
const bmi=document.getElementById('bmi').value

const note=

"Clinical Measurements\n"+
"Weight: "+weight+" kg\n"+
"Height: "+height+" cm\n"+
"BMI: "+bmi+"\n\n"+

"Chief Complaint:\n"+chief+"\n\n"+
"Care Logs:\n"+logs+"\n\n"+
"Medical History:\n"+history+"\n\n"+
"Medications:\n"+meds+"\n\n"+

"O:\n"+objective+"\n\n"+
"A:\n"+assessment+"\n\n"+
"P:\n"+plan

document.getElementById('finalNote').value=note

})

</script>

@endsection
