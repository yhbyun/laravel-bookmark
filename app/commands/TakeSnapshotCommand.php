<?php namespace App\Commands;

use App\Bookmark;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
Use Image;
use JonnyW\PhantomJs\Client;
use Log;

class TakeSnapshotCommand extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

    public $bookmark;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Bookmark $bookmark)
	{
        $this->bookmark = $bookmark;
	}

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $bookmark = $this->bookmark;
        $client = Client::getInstance();
        $client->setBinDir(base_path('bin'));
        $client->addOption('--ignore-ssl-errors=true');
        $client->addOption('--ssl-protocol=any');
        $client->addOption('--web-security=false');
        //$client->debug(true);

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $width  = 800;
        $height = 800;
        $captureFile = public_path("images/screenshots/{$bookmark->id}.png");
        $thumbFile = public_path("images/screenshots/{$bookmark->id}_thumb.png");

        $request->setMethod('GET');
        $request->setUrl($bookmark->url);
        $request->setCaptureFile($captureFile);
        $request->setCaptureDimensions($width, $height);
        $request->setViewportSize($width, $height);

        $timeout = 20000; // 20 seconds
        $request->setTimeout($timeout);

        //$delay = 5; // 5 seconds
        //$request->setDelay($delay);

        $client->send($request, $response);

        $status = $response->getStatus();

        if ($status === 200 || $status === 301 || $status === 302) {
            $img = Image::make($captureFile);
            $img->widen(228)->crop(228, 160, 0, 0);
            $img->save($thumbFile);

            $bookmark->thumbnail = 1;
            $bookmark->save();

            Log::info('TakeSnapshot completed. log:' . $client->getLog());
        } else {
            Log::error('TakeSnapshot error. response status : ' . $status . ', log:' . $client->getLog());
        }
    }
}
