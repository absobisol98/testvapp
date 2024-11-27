<?php

namespace App\Actions;

use Zxing\QrReader;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use claviska\SimpleImage;


class GenerateEventQRCode
{
    public function execute($attendee)
    {
        $logo_path = getcwd() . '/img/ayala-x-logo.png';
        // First, read in the logo file and downscale it to max 100px
        $logoImageReader = new SimpleImage();
        $logoImageReader
            ->fromFile($logo_path)
            ->bestFit(100, 100);

        // Next, create a slightly larger image,
        // fill it with a rounded white square,
        // and overlay the resized logo
        $logoImageBuilder = new SimpleImage();
        $logoImageBuilder
            ->fromNew(130, 130)
            ->roundedRectangle(0, 0, 130, 130, 20, 'white', 'filled',)
            ->overlay($logoImageReader);

        // Grab the reformatted logo as a Data URI
        // that we can feed into Endroid QR-code
        $logoData = $logoImageBuilder->toDataUri('image/png', 100);


        $writer = new PngWriter();

        // Create QR code
        $qrCode = new QrCode(
            data: route('qr.scan', ['event_id' => $attendee->event_id,'attendee_id' => $attendee->id]),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 600,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(255, 115, 3),
            backgroundColor: new Color(255, 255, 255)
        );

        // Create generic logo
        $logo = new Logo(
            path: $logoData,
            resizeToWidth: 120,
            punchoutBackground: false
        );

        $qrcode = new QrReader($logo_path);
        $text = $qrcode->text(); //return decoded text from QR Code


        // Create generic label
        $label = new Label(
            text: 'Label',
            textColor: new Color(255, 0, 0)
        );

        $result = $writer->write($qrCode, $logo, null);

        // Validate the result
        $writer->validateResult($result,route('qr.scan', ['event_id' => $attendee->event_id,'attendee_id' => $attendee->id]));

        $result->saveToFile(storage_path('app/public/'.$attendee->id.'-qr-code.png'));
    }
}
