<section class="flex overflow-hidden flex-col justify-center items-center px-20 py-64 text-black bg-white max-md:px-5 max-md:py-24">
    <div class="flex flex-col max-w-full w-[631px]">
        <header class="flex flex-col pr-2.5 pl-20 max-md:pl-5 max-md:max-w-full">
            <h1 class="self-center ml-5 text-sm">Interview Application</h1>
            <article class="flex flex-col self-start pt-6 pr-20 pb-10 pl-3 mt-5 border border-black border-solid max-md:pr-5 max-md:max-w-full">
                <p class="self-start text-sm">Job Posting Number: {{ $interview->vacancy_id }}</p>
                <p class="self-start mt-8 ml-20 text-xl max-md:ml-2.5">Company Name: {{ $interview->vacancy->companyRepresentative->company->name }}</p>
                <p class="self-start mt-6 ml-20 text-xl max-md:ml-2.5">Person in charge: {{ $interview->vacancy->getPersonInCharge() }}</p>
                <p class="self-center mt-8 text-3xl text-center w-[266px]">
                    {{ $interview->implementation_date->format('Y F d') }}<br />
                    {{ $interview->implementation_start_time->format('H:i') }}
                </p>
                <p class="self-center mt-14 text-base text-center max-md:mt-10">Interview Number: {{ $interview->id }}</p>
                <p class="self-center mt-8 text-base text-center">
                    Your application has been submitted with the above details.<br />
                    Please wait for the company to confirm the schedule.
                </p>
            </article>
        </header>
        <section class="mt-11 text-sm max-md:mt-10 max-md:max-w-full">
            <p>• The interview will be conducted via Zoom.</p>
            <p>• Please make a note of the date, time, person in charge, and interview number. (You can also check this information in the interview status list on your My Page.)</p>
            <p>• The Zoom ID will be sent to your registered email address by 12:00 PM Japan time on the day before the interview.</p>
            <p>• If you do not receive the email, please contact the administration office.</p>
            <p>• For Zoom installation and usage instructions, click here</p>
        </section>
        <footer class="flex flex-col items-center pr-2.5 pl-20 w-full text-xl text-center whitespace-nowrap max-md:pl-5 max-md:max-w-full">
            <hr class="shrink-0 h-px border border-black border-solid w-[41px]" />
            <nav class="flex flex-col items-center">
                <a href="{{ route('student.interview-status') }}" class="px-10 py-2.5 mt-24 ml-8 max-w-full bg-white rounded-md border border-black border-solid w-[200px] max-md:px-5 max-md:mt-10">
                    Interview Status List
                </a>
                <a href="{{ route('student.cancel-interview', $interview) }}" class="px-9 py-2.5 mt-11 ml-5 max-w-full bg-white rounded-md border border-black border-solid w-[269px] max-md:px-5 max-md:mt-10">
                    Cancel Application
                </a>
            </nav>
            <nav class="flex flex-wrap gap-9 self-start mt-24 text-sm max-md:mt-10">
                <a href="{{ route('student.job-details', $interview->vacancy) }}" class="px-8 py-3 bg-white rounded-md border border-black border-solid max-md:px-5">
                    Return to Job Details
                </a>
                <a href="{{ route('student.job-search') }}" class="px-7 py-3 bg-white rounded-md border border-black border-solid max-md:px-5">
                    Return to Job Search
                </a>
                <a href="{{ route('home') }}" class="px-5 py-3 bg-white rounded-md border border-black border-solid max-md:px-5">
                    Return to TOP Page
                </a>
            </nav>
        </footer>
    </div>
</section>
