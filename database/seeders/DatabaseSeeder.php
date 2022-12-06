<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		User::truncate();
		Movie::query()->delete();
		Notification::query()->delete();

		$user = User::create([
			'username'        => 'irakli',
			'email'           => 'irakli@irakli.ge',
			'password'        => 'irakliirakli',
			'profile_picture' => '/storage/images/profile/darth_vader_default_profile.png',
		]);
		$user->markEmailAsVerified();

		$user_two = User::create([
			'username'        => 'yoda',
			'email'           => 'yoda@irakli.ge',
			'password'        => 'irakliirakli',
			'profile_picture' => '/storage/images/profile/darth_vader_default_profile.png',
		]);
		$user_two->markEmailAsVerified();

		$shrek = Movie::create([
			'image'   => '/storage/images/movies/shrek.jpg',
			'genres'  => json_encode(['Animation', 'Family']),
			'user_id' => $user->id,
			'title'   => [
				'en' => 'Shrek',
				'ka' => 'შრეკი',
			],
			'director' => [
				'en' => 'William Steig',
				'ka' => 'უილიამ სტეიგი',
			],
			'description' => [
				'en' => 'Shrek, animated cartoon character, a towering, green ogre whose fearsome appearance belies a kind heart. Shrek is the star of a highly successful series of animated films. At the beginning of the 2001 film Shrek, the title character lives as a recluse in a remote swamp in the fairy-tale land of Duloc.',
				'ka' => 'იყო და არა იყო ერთ ზღაპრულ ქვეყანაში დიდი გოლიათი სახელად შრეკი. ცხოვრობდა ის სრულ სიმარტოვეში ტყეში, ჭაობში, რომელიც საკუთარ ჭაქობად მიაჩნდა. მაგრამ ერთხელაც ბოროტმა ლილიპუტმა – ლორდ ფარკაუდმა, ჯადოსნური სამეფოს მმართველმა, დაუნდობლად გაყარა შრეკის ჭაობში ყველა ზღაპრული ბინადარი. და მწვანე ტროლის ბედნიერ ცხოვრებას ბოლოც მოეღო. ლორდ ფარკაუდი დაპირდა შრეკს ჭაობის დაბრუნებას, თუ კი გოლიათი მას მშვენიერ პრინცესა ფიონას მოუყვანს, რომელიც იტანჯება კოშკში, რომელსაც ცეცხლის მფრ ...',
			],
		]);
		$sevenSamurai = Movie::create([
			'image'   => '/storage/images/movies/seven_samurai.jpg',
			'genres'  => json_encode(['Action', 'Adventure', 'Epic']),
			'user_id' => $user_two->id,
			'title'   => [
				'en' => 'Seven Samurai',
				'ka' => 'შვიდი სამურაი',
			],
			'director' => [
				'en' => 'Akira Kurosawa',
				'ka' => 'აკირა კუროსავა',
			],
			'description' => [
				'en' => 'Seven Samurai (Japanese: 七人の侍, Hepburn: Shichinin no Samurai) is a 1954 Japanese epic samurai drama film co-written, edited, and directed by Akira Kurosawa. The story takes place in 1586[a] during the Sengoku period of Japanese history. It follows the story of a village of desperate farmers who hire seven rōnin (masterless samurai) to combat bandits who will return after the harvest to steal their crops.',
				'ka' => 'შვიდი სამურაი — 1954 წლის იაპონური სათავგადასავლო დრამატული ფილმი. მისი რეჟისორი, სცენარის და მონტაჟის ავტორია აკირა კუროსავა. ფილმში მიმდინარე მოვლენები ხდება 1587 წელს, იაპონიაში. შვიდი სამურაო კინოს ისტორიაში ერთ-ერთ ყველაზე გავლენიან ფილმად მიიჩნევა. მისი დასავლური გადამუშავებული ვერსიაა ვესტერნი „შესანიშნავი შვიდეული“. ფილმმა მიიღო ვენეციის კინოფესტივალის ვერცხლის ლომი.,',
			],
		]);
		$harakiri = Movie::create([
			'image'   => '/storage/images/movies/harakiri.jpg',
			'genres'  => json_encode(['Drama', 'Epic', 'Jidaigeki']),
			'user_id' => $user->id,
			'title'   => [
				'en' => 'Harakiri',
				'ka' => 'ჰარაკირი',
			],
			'director' => [
				'en' => 'Masaki Kobayashi',
				'ka' => 'მასაკი კობაიაში',
			],
			'description' => [
				'en' => "The film takes place in Edo in the year 1630. Tsugumo Hanshirō arrives at the estate of the Iyi clan and says that he wishes to commit seppuku within the courtyard of the palace. To deter him, Saitō Kageyu (Rentarō Mikuni), the daimyō's senior counselor, tells Hanshirō the story of another rōnin, Chijiiwa Motome—formerly of the same clan as Hanshirō.",
				'ka' => 'მეჩვიდმეტე საუკუნის იაპონიაში სიმშვიდეა, ეს კი იწვევს ათასობით სამურაის უმუშევრად, სიღარიბეში ყოფნას. ამ ყოფის ღირსეული დასასრული კი სიცოცხლის თვითმკვლელობით ან ჰარაკირით დასრულებაა.',
			],
		]);

		Quote::create([
			'user_id'  => $user->id,
			'movie_id' => $shrek->id,
			'quote'    => [
				'en' => 'Ogres are like onions',
				'ka' => 'ოგრები ხახვებივით არიან',
			],
			'thumbnail' => '/storage/images/quotes/shrek_ogres.jpg',
		]);

		Quote::create([
			'user_id'  => $user->id,
			'movie_id' => $harakiri->id,
			'quote'    => [
				'en' => 'The suspicious mind conjures its own demons.',
				'ka' => 'ეჭვიანი გონება საკუთარ დემონებს თავად შეიპყრობს',
			],
			'thumbnail' => '/storage/images/quotes/harakiri_suspicious.jpg',
		]);
		Quote::create([
			'user_id'  => $user->id,
			'movie_id' => $harakiri->id,
			'quote'    => [
				'en' => 'After all, this thing we call samurai honor is ultimately nothing but a facade',
				'ka' => 'საბოლოოდ ის რასაც სამურაის ღირსებას ვუწოდებთ მხოლოდ და მხოლოდ ფასადია',
			],
			'thumbnail' => '/storage/images/quotes/harakiri_facade.jpeg',
		]);
		Quote::create([
			'user_id'  => $user_two->id,
			'movie_id' => $sevenSamurai->id,
			'quote'    => [
				'en' => "Don't you see? A real sword will kill you",
				'ka' => 'ვერ ხვდები? ნამდვილი ხმალი მოგკლავდა',
			],
			'thumbnail' => '/storage/images/quotes/seven_samurai_sword.png',
		]);
		Quote::create([
			'user_id'  => $user->id,
			'movie_id' => $harakiri->id,
			'quote'    => [
				'en' => 'Swordsmanship untested in battle is like the art of swimming mastered on land.',
				'ka' => 'ხმლის ოსტატობა გამოუცდელი ბრძოლაში იგივეა რაც ცურვის ხელოვნება მიწაზე აითვისო',
			],
			'thumbnail' => '/storage/images/quotes/harakiri_swordsmanship.jpg',
		]);
	}
}
