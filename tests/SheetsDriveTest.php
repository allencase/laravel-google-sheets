<?php

namespace Tests;

use Mockery as m;
use Revolution\Google\Sheets\Facades\Sheets;

use \Google_Service_Drive_DriveFile;

class SheetsDriveTest extends TestCase
{
    /**
     * @var \Google_Service_Drive
     */
    protected $service;

    /**
     * @var \Google_Service_Drive_Resource_Files
     */
    protected $files;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = m::mock('Google_Service_Drive');
        $this->files = m::mock('Google_Service_Drive_Resource_Files');
        $this->service->files = $this->files;

        Sheets::setDriveService($this->service);
    }

    public function testList()
    {
        $file = new Google_Service_Drive_DriveFile([
            'id'   => 'id',
            'name' => 'name',
        ]);

        $files = [
            $file,
        ];

        $this->files->shouldReceive('listFiles->getFiles')->once()->andReturn($files);

        $list = Sheets::spreadsheetList();

        $this->assertSame(['id' => 'name'], $list);
    }

    public function testListNull()
    {
        $this->files->shouldReceive('listFiles->getFiles')->never();

        $list = Sheets::setDriveService(null)->spreadsheetList();

        $this->assertSame([], $list);
    }
}
