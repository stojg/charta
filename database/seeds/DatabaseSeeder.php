<?php

use App\Document;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('DocumentTableSeeder');
		$this->command->info('Documents created!');
	}

}

class DocumentTableSeeder extends Seeder {

	public function run()
	{
		DB::table('documents')->delete();

		Document::create([
			'title' => 'Metrics Collection',
			'content' => file_get_contents('database/seeds/document/first-document.md')
		]);

		Document::create([
			'title' => 'Centralised logging',
			'content' => file_get_contents('database/seeds/document/second-document.md')
		]);

		Document::create([
			'title' => 'SSP Emotion Kit',
			'content' => file_get_contents('database/seeds/document/third-document.md')
		]);
	}

}

