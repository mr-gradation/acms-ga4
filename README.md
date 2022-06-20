# acms-ga4
a-blog cmsのサイトにGoogle Analytics 4のランキングを表示することができる拡張アプリです。

## ダウンロード
[acms-ga4](https://github.com/mr-gradation/acms-ga4/releases/download/1.0.0/acms-ga4-1.0.0.zip)

## インストール
1. ダウンロード後、`extension/plugins/GA4` に設置します。
2. 管理ページ > 拡張アプリのページに移動し、GA4 をインストールします。

## 使い方

### Google Cloud Platformの準備

1. Google Cloud Platformにてプロジェクトを作成
2. APIとサービスより、「APIとサービスの有効化」をクリック
3. APIライブラリより、「Google Analytics Data API」を有効にする
4. 認証情報より、「認証情報を作成」をクリックし、サービスアカウントを作成
5. 作成したサービスアカウントをクリックすると、サービスアカウントの詳細画面に遷移するので「キー」タブに切り替え
6. 「鍵を追加」ボタンをクリックし、「新しい鍵を作成」を選択。キーのタイプはJSON形式でダウンロード

### Google Analytics 4の準備

1. Google Analytics 4のプロパティを開き、管理に移動
2. 「プロパティのアクセス管理」の画面を開く
3. Google Cloud Platformで追加したサービスアカウントを追加

### a-blog cmsの準備

1. Google Cloud PlatformからダウンロードしたJSONファイルをサーバの任意の場所にアップロード
2. 管理ページに「GA4」のメニューが追加されているので、JSONファイルのパスと、GA4のプロパティID（プロパティ設定からコピー可能）を指定
3. モジュールIDを作成すると、表示件数、集計開始日、集計終了日、検索条件を設定することができる
4. テンプレートに `GA4Ranking` モジュールを貼り付ける

## モジュールの使用例

### GA4Rankingモジュールのみ

```
<!-- BEGIN_MODULE GA4Ranking id="top_ranking2" -->
<table border="1">
  <!-- BEGIN ranking:loop -->
  <tr>
    <td>{title}</td>
    <td>{path}</td>
    <td>{views}</td>
  </tr>
  <!-- END ranking:loop -->
</table>
<!-- END_MODULE GA4Ranking -->
```

### Entry_Summaryと組み合わせる場合

```
<!-- BEGIN_MODULE GA4Ranking id="top_ranking" -->
<ul class="ranking-list">
  <!-- BEGIN ranking:loop -->
  <!-- BEGIN_IF [{path}[path2eid]/nem] -->
  <!-- BEGIN_MODULE\ Entry_Summary ctx="bid/1/eid/{path}[path2eid]" -->
  <!-- BEGIN\ unit:loop -->
  <!-- BEGIN\ entry:loop -->
  <li class="item">
    <a href="\{url\}" class="ranking-card">
      <figure class="thumbnail">
        <!-- BEGIN_IF [\{entry_thumbnail@path\}/nem] -->
        <img src="%{MEDIA_ARCHIVES_DIR}\{entry_thumbnail@path\}[resizeImg(200,133)]" alt="">
        <!-- ELSE -->
        <!-- BEGIN image:veil -->
        <img src="%{ROOT_DIR}\{path\}[resizeImg(200,133)]" alt="">
        <!-- END image:veil -->
        <!-- BEGIN noimage -->
        <img src="/images/nophoto.png" alt="">
        <!-- END noimage -->
        <!-- END_IF -->
      </figure>
      <dl class="body">
        <dt class="title"><span>\{title\}</span></dt>
        <dd class="meta">
          <span class="date">\{date#Y\}.\{date#m\}.\{date#d\}</span>
          <span class="pageview">{views}[number_format]</span>
        </dd>
      </dl>
    </a>
  </li>
  <!-- END\ entry:loop -->
  <!-- END\ unit:loop -->
  <!-- END_MODULE\ Entry_Summary -->
  <!-- END_IF -->
  <!-- END ranking:loop -->
</ul>
<!-- END_MODULE GA4Ranking -->
```
