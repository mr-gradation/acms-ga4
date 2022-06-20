<?php

namespace Acms\Plugins\GA4\GET;

use ACMS_GET;
use Template;
use ACMS_Corrector;

require __DIR__.'/../vendor/autoload.php';
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\StringFilter;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . config('google_application_credentials'));

class GA4Ranking extends ACMS_GET
{

  function get()
  {
    
    $Tpl = new Template($this->tpl, new ACMS_Corrector());
    $this->buildModuleField($Tpl);

    // リクエストのパラメーターを組み立て
    $request = [
      'property' => 'properties/' . config('ga4_property_id'),
      'limit' => config('ga4_ranking_max_results', 10),
      'dateRanges' => [
        new DateRange([
          'start_date' => config('ga4_ranking_start_date', '7daysAgo'),
          'end_date' => config('ga4_ranking_end_date', 'today'),
        ]),
      ],
      'dimensions' => [
        new Dimension([
          'name' => 'pagePath',
        ]),
        new Dimension([
          'name' => 'pageTitle',
        ]),
      ],
      'metrics' => [
        new Metric([
          'name' => 'screenPageViews',
        ])
      ]
    ];

    // 一致する条件のフィルタを組み立て
    if (config('ga4_ranking_filter')) {
      $expressions[] = new FilterExpression([
        "filter" => new Filter([
          'field_name' => 'pagePath',
          'string_filter' => new Filter\StringFilter([
            'match_type' => Filter\StringFilter\MatchType::CONTAINS,
            'value' => config('ga4_ranking_filter'),
          ])
        ]),
      ]);
    }

    // 除外する条件のフィルタを組み立て
    if (config('ga4_ranking_exclude_filter')) {
      $expressions[] = new FilterExpression([
        "not_expression" => new FilterExpression([
          "filter" => new Filter([
            'field_name' => 'pagePath',
            'string_filter' => new Filter\StringFilter([
              'match_type' => Filter\StringFilter\MatchType::CONTAINS,
              'value' => 'test',
            ])
          ]),
        ]),
      ]);
    }

    // 一致または除外の条件があればフィルタを追加
    if (config('ga4_ranking_filter') || config('ga4_ranking_exclude_filter')) {
      $request['dimensionFilter'] = new FilterExpression([
        "and_group" => new FilterExpressionList([
          "expressions" => $expressions
        ])
      ]);
    }

    // レポートを取得
    $client = new BetaAnalyticsDataClient();
    $response = $client->runReport($request);

    // 何もなければnotFoundを表示
    if ( count($response->getRows()) === 0 ) {
        $Tpl->add('notFound');
        return $Tpl->get();
    }

    // データがあればranking:loopを表示
    foreach ( $response->getRows() as $row ) {
      $Tpl->add('ranking:loop', array(
          'title' => $row->getDimensionValues()[1]->getValue(),
          'path'  => $row->getDimensionValues()[0]->getValue(),
          'views' => $row->getMetricValues()[0]->getValue(),
      ));
    }

    return $Tpl->get();
  }
}
