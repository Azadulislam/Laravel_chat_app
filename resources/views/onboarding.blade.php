@extends('layouts.app')

@section('content')
<div x-data="{ step: 1, agreed: false, agreed2: false }" class="flex items-center justify-center py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">

        <div class="bg-white rounded-sm border border-slate-200 p-8">
            <div x-show="step === 1" x-transition>
                <div class="text-slate-700 space-y-4 mb-8">
                    <h2 class="text-2xl text-center font-extrabold text-slate-900 tracking-tight">
                        Welcome to the Team
                    </h2>
                    <p>
                        I work for a web design and SEO agency, and throughout the year I will be assigning you various projects and tasks. You will be collaborating with a team of 20 designers and SEO specialists, each with expertise in different areas.
                    </p>
                    <p>
                        Team collaboration is essential. Every team member is expected to review, comment on, and suggest improvements for each other’s work on a regular basis. Everyone should provide feedback on teammates’ projects at least 5–10 times per week.
                    </p>
                    <p>
                        This collaborative approach will accelerate the website development process and help us achieve the highest possible quality in both design and SEO performance. The goal is continuous improvement through teamwork — because 20 minds working together are far more effective than one.
                    </p>
                    <p>
                        Remember: this is a collective effort. Everyone is expected to support, help, and learn from one another.
                    </p>
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input x-model="agreed" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" id="agree">
                        <label class="ml-2 block text-sm text-slate-600 font-medium" for="agree">
                            I have read and understand the introduction
                        </label>
                    </div>
                </div>

                <button x-bind:disabled="!agreed" @click="step = 2" x-bind:class="{ 'opacity-50 cursor-not-allowed': !agreed, 'btn-primary': agreed }" class="w-full py-2 px-4 text-white font-semibold rounded-sm text-lg disabled:bg-slate-400">
                    Next
                </button>
            </div>

            <div x-show="step === 2" x-transition>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Website Design Team Questionnaire</h3>

                <div class="text-slate-700">
                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Personal Introduction:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>What is your full name?</li>
                            <li>What should the team call you?</li>
                            <li>Where are you based?</li>
                            <li>Tell us about yourself</li>
                            <li>What inspired you to join web design/development?</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Professional Background:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Main role (UI/UX Designer, Frontend Developer, Backend Developer, Shopify Developer, WordPress Developer, Graphic Designer, Project Manager, Other)</li>
                            <li>Years of experience</li>
                            <li>Technologies used</li>
                            <li>Tools/software used</li>
                            <li>Strongest skills</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Work Preferences:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Favorite project type</li>
                            <li>Team or solo preference</li>
                            <li>Time zone & working hours</li>
                            <li>Communication method (WhatsApp / Slack / Telegram / Email / Discord / Other)</li>
                            <li>Deadline handling</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Team Collaboration:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Expectations from teammates</li>
                            <li>What makes a good team</li>
                            <li>Handling feedback</li>
                            <li>Willingness to learn new tools</li>
                            <li>Future role goal</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Portfolio & Experience:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Portfolio links</li>
                            <li>Best project</li>
                            <li>International client experience</li>
                            <li>Agency/remote experience</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Fun Section:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Hobbies</li>
                            <li>Interesting fact</li>
                            <li>Motivation</li>
                            <li>Favorite design style (Minimal / Luxury / Modern / Corporate / Creative / Other)</li>
                        </ul>
                    </div>

                    <div class="p-2">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Final Section:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Why are you a good fit?</li>
                            <li>Anything else?</li>
                        </ul>
                    </div>

                    <div class="p-2 bg-slate-50 rounded-sm">
                        <h4 class="font-semibold text-lg text-slate-800 mb-2">Send Answer to the Email:</h4>
                        <p class="text-blue-600">admin@yourdomain.com</p>
                        <p class="text-sm text-slate-600 mt-2">Please copy this questionnaire and send it to the email.</p>
                    </div>
                </div>

                <div class="mb-6 mt-8">
                    <div class="flex items-center">
                        <input x-model="agreed2" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded" id="agree2">
                        <label class="ml-2 block text-sm text-slate-600 font-medium" for="agree2">
                            I have sent the answer to the email
                        </label>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button @click="step = 1" class="flex-1 py-2 px-4 text-slate-700 font-semibold rounded-sm text-lg border border-slate-300 hover:bg-slate-50 transition-colors">
                        Back
                    </button>
                    <form method="POST" action="{{ route('onboarding.complete') }}" class="flex-1">
                        @csrf
                        <button type="submit" x-bind:disabled="!agreed2" x-bind:class="{ 'opacity-50 cursor-not-allowed': !agreed2, 'btn-primary': agreed2 }" class="w-full py-2 px-4 text-white font-semibold rounded-sm text-lg disabled:bg-slate-400">
                            Complete Onboarding
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
