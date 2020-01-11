<?php

use App\Enums\Buildingside;
use App\Enums\Buildingtype;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('buildings')->insert(
            ['id' => Buildingtype::Trout, 'name' => 'Trout Lodge Guest Room', 'image' => 'guestroom1.jpg;guestroom2.jpg', 'blurb' => __(Buildingtype::Trout), 'side' => Buildingside::Trout],
            ['id' => Buildingtype::Lakeview, 'name' => 'Lakeview Cabins', 'image' => 'lakeview1.jpg;lakeview2.jpg;lakeview3.jpg', 'blurb' => __(Buildingtype::Lakeview), 'side' => Buildingside::Trout],
            ['id' => Buildingtype::Forestview, 'name' => 'Forestview Cabins', 'image' => 'forestview1.jpg;forestview2.jpg;forestview3.jpg', 'blurb' => __(Buildingtype::Forestview), 'side' => Buildingside::Trout],
            ['id' => Buildingtype::Loft, 'name' => 'Trout Lodge Loft Suite', 'image' => 'loftsuite1.jpg;loftsuite2.jpg;loftsuite3.jpg', 'blurb' => __(Buildingtype::Loft), 'side' => Buildingside::Trout],
            ['id' => Buildingtype::Tent, 'name' => 'Tent Camping', 'blurb' => __(Buildingtype::Trout), 'side' => Buildingside::Tent],
            ['id' => Buildingtype::LakewoodYA, 'name' => 'Camp Lakewood Young Adults', 'blurb' => __(Buildingtype::LakewoodYA), 'side' => Buildingside::Lakewood],
            ['id' => Buildingtype::LakewoodSr, 'name' => 'Camp Lakewood Sr. High Cabin', 'blurb' => __(Buildingtype::LakewoodSr), 'side' => Buildingside::Lakewood],
            ['id' => Buildingtype::LakewoodJr, 'name' => 'Camp Lakewood Jr. High Cabin', 'blurb' => __(Buildingtype::LakewoodJr), 'side' => Buildingside::Lakewood],
            ['id' => Buildingtype::LakewoodCabin, 'name' => 'Camp Lakewood Cabins', 'image' => 'cabin171.jpg;cabin172.jpg', 'blurb' => __(Buildingtype::LakewoodCabin), 'side' => Buildingside::Lakewood],
            ['id' => Buildingtype::Commuter2x, 'name' => 'Commuter (2 meal)', 'blurb' => __(Buildingtype::Commuter2x), 'side' => Buildingside::Commuter],
            ['id' => Buildingtype::Commuter3x, 'name' => 'Commuter (3 meal)', 'blurb' => __(Buildingtype::Commuter3x), 'side' => Buildingside::Commuter]
        );
    }
}
