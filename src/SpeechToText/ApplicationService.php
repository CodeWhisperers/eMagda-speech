<?php

namespace SpeechToText;

use Google_Client;
use Google_Service_CloudSpeechAPI;
use Google_Service_CloudSpeechAPI_RecognitionAudio;
use Google_Service_CloudSpeechAPI_RecognitionConfig;
use Google_Service_CloudSpeechAPI_SyncRecognizeRequest;

class ApplicationService
{

    /**
     * @param $audio
     * @param $sampleRate
     *
     * @return array
     */
    public function run($audio, $sampleRate)
    {

        $a = new Google_Service_CloudSpeechAPI_RecognitionAudio();
        $a->setContent($audio);

        $c = new Google_Service_CloudSpeechAPI_RecognitionConfig();
        $c->setEncoding('LINEAR16');
        $c->setSampleRate($sampleRate);
        $c->setLanguageCode('ro-RO');
        $c->setMaxAlternatives(1);

        $request = new Google_Service_CloudSpeechAPI_SyncRecognizeRequest();
        $request->setAudio($a);
        $request->setConfig($c);

        $client = new Google_Client();
        $client->setApplicationName(getenv('SPEECH_API_APP'));
        $client->setDeveloperKey(getenv('SPEECH_API_KEY'));

        $service = new Google_Service_CloudSpeechAPI($client);
        $response = $service->speech->syncrecognize($request);
        $results = $response->getResults();

        if (count($results) > 0) {

            $result = $results[0];
            $result = $result->getAlternatives();
            $result = $result[0]['transcript'];

        } else {

            $result = false;

        }

        return array($result);

    }

}