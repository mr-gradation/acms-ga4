<?php

namespace Acms\Plugins\GA4;

use DB;
use SQL;

class Corrector
{
  /**
   * path2eid
   * Api_GoogleAnalytics_Rankingモジュールの {path} から eid を取得する
   *
   * @param  string $path -
   * @return string       - eid
   */
  public function path2eid($path)
  {
    $path = basename($path);
    $DB = DB::singleton(dsn());
    $SQL = SQL::newSelect('entry');
    $SQL->addSelect('entry_id');
    $SQL->addWhereOpr('entry_code', $path, '=');
    $q = $SQL->get(dsn());
    $eid = $DB->query($q, 'one');
    if ($eid) {
      return $eid;
    } else {
      return NULL;
    }
  }
}
