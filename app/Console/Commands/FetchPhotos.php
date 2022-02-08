<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Photo;

class FetchPhotos extends Command
{
    private const API_URL = 'https://jsonplaceholder.typicode.com/photossss';
    private const RETRIES = 5;
    private const SLEEP_TIME = 250;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the API available photos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $inserts = 0;
            $updates = 0;
            $response = Http::retry(self::RETRIES, self::SLEEP_TIME)->get(self::API_URL);

            $data = json_decode($response->body(), true);

            foreach($data as $datum)
            {
                $photoId = collect($datum)->get('id');
                if($photo = Photo::find($photoId)) {
                    $photo->update($datum);
                    $updates++;
                }else{
                    Photo::create($datum);
                    $inserts++;
                }
            }

            $this->info("Inserts: $inserts - Updates: $updates");
            $this->info("Data succesfully imported!");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return 1;
        }

        return 0;
    }
}
