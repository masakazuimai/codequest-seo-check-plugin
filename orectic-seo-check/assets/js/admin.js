/**
 * ORECTIC SEO CHECK - 管理画面JavaScript
 */
(function ($) {
  'use strict';

  var CIRCUMFERENCE = 2 * Math.PI * 54; // 339.292

  var categoryLabels = {
    structured_data: cqseoData.i18n.structuredData,
    basic_seo: cqseoData.i18n.basicSeo,
    content: cqseoData.i18n.content,
    technical: cqseoData.i18n.technical,
  };

  var statusIcons = {
    good: '\u2713',
    warning: '\u0021',
    error: '\u2717',
  };

  var statusLabels = {
    good: cqseoData.i18n.good,
    warning: cqseoData.i18n.warning,
    error: cqseoData.i18n.errorStatus,
  };

  /**
   * スコアに応じたCSSクラスを返す
   */
  function getScoreClass(score, max) {
    var percentage = max > 0 ? (score / max) * 100 : 0;
    if (percentage >= 70) return 'score-high';
    if (percentage >= 40) return 'score-mid';
    return 'score-low';
  }

  /**
   * 円グラフのスコアを更新
   */
  function updateScoreCircle(score, maxScore) {
    var percentage = maxScore > 0 ? score / maxScore : 0;
    var offset = CIRCUMFERENCE * (1 - percentage);
    var $bar = $('#cqseo-score-bar');

    $bar
      .removeClass('score-high score-mid score-low')
      .addClass(getScoreClass(score, maxScore));

    // アニメーション用に一度リセット
    $bar.css('stroke-dashoffset', CIRCUMFERENCE);

    setTimeout(function () {
      $bar.css('stroke-dashoffset', offset);
    }, 50);

    $('#cqseo-score-value').text(score);
  }

  /**
   * カテゴリ別スコアを描画
   */
  function renderCategories(categories) {
    var $container = $('#cqseo-categories');
    $container.empty();

    var order = ['structured_data', 'basic_seo', 'content', 'technical'];

    order.forEach(function (key) {
      if (!categories[key]) return;

      var cat = categories[key];
      var percentage = cat.max > 0 ? (cat.score / cat.max) * 100 : 0;

      var html =
        '<div class="cqseo-category-card">' +
        '<div class="cqseo-category-name">' +
        escapeHtml(categoryLabels[key] || key) +
        '</div>' +
        '<div>' +
        '<span class="cqseo-category-score">' +
        cat.score +
        '</span>' +
        '<span class="cqseo-category-max"> / ' +
        cat.max +
        '</span>' +
        '</div>' +
        '<div class="cqseo-category-bar">' +
        '<div class="cqseo-category-bar-fill" style="width:0%;" data-width="' +
        percentage +
        '%"></div>' +
        '</div>' +
        '</div>';

      $container.append(html);
    });

    // バーのアニメーション
    setTimeout(function () {
      $container.find('.cqseo-category-bar-fill').each(function () {
        $(this).css('width', $(this).data('width'));
      });
    }, 100);
  }

  /**
   * チェック項目一覧を描画
   */
  function renderChecks(checks) {
    var $container = $('#cqseo-checks');
    $container.empty();

    // カテゴリ順にグループ化
    var order = ['structured_data', 'basic_seo', 'content', 'technical'];
    var grouped = {};

    checks.forEach(function (check) {
      var cat = check.category || 'other';
      if (!grouped[cat]) {
        grouped[cat] = [];
      }
      grouped[cat].push(check);
    });

    order.forEach(function (catKey) {
      if (!grouped[catKey] || grouped[catKey].length === 0) return;

      $container.append(
        '<div class="cqseo-checks-category-header">' +
          escapeHtml(categoryLabels[catKey] || catKey) +
          '</div>'
      );

      grouped[catKey].forEach(function (check) {
        var suggestionHtml = check.suggestion
          ? '<div class="cqseo-check-suggestion">' +
            escapeHtml(check.suggestion) +
            '</div>'
          : '';

        var html =
          '<div class="cqseo-check-item">' +
          '<div class="cqseo-check-status cqseo-status-' +
          check.status +
          '">' +
          statusIcons[check.status] +
          '</div>' +
          '<div class="cqseo-check-info">' +
          '<div class="cqseo-check-name">' +
          escapeHtml(check.name) +
          '</div>' +
          '<div class="cqseo-check-message">' +
          escapeHtml(check.message) +
          '</div>' +
          suggestionHtml +
          '</div>' +
          '<div class="cqseo-check-score">' +
          check.score +
          ' / ' +
          check.max_score +
          '</div>' +
          '</div>';

        $container.append(html);
      });
    });
  }

  /**
   * HTMLエスケープ
   */
  function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
  }

  /**
   * 診断実行
   */
  function runCheck() {
    var url = $('#cqseo-url').val().trim();
    if (!url) return;

    var $btn = $('#cqseo-run-check');
    var $loading = $('#cqseo-loading');
    var $error = $('#cqseo-error');
    var $results = $('#cqseo-results');

    $btn.prop('disabled', true).text(cqseoData.i18n.checking);
    $loading.show();
    $error.hide();
    $results.hide();

    $.ajax({
      url: cqseoData.ajaxUrl,
      type: 'POST',
      data: {
        action: 'cqseo_run_check',
        nonce: cqseoData.nonce,
        url: url,
      },
      timeout: 70000,
      success: function (response) {
        if (response.success && response.data) {
          displayResults(response.data);
        } else {
          var msg =
            response.data && response.data.message
              ? response.data.message
              : cqseoData.i18n.error;
          showError(msg);
        }
      },
      error: function (xhr, status) {
        var msg =
          status === 'timeout'
            ? cqseoData.i18n.timeout
            : cqseoData.i18n.error;
        showError(msg);
      },
      complete: function () {
        $btn.prop('disabled', false).text(cqseoData.i18n.runCheck);
        $loading.hide();
      },
    });
  }

  /**
   * 結果を表示
   */
  function displayResults(data) {
    updateScoreCircle(data.score, data.maxScore);
    renderCategories(data.categories);
    renderChecks(data.checks);

    // 無料枠の残り回数表示
    var $remaining = $('#cqseo-free-remaining');
    if (typeof data.anonymousUsed !== 'undefined') {
      var freeLimit = cqseoData.freeLimit;
      var remaining = freeLimit - data.anonymousUsed;
      remaining = remaining >= 0 ? remaining : 0;
      $remaining
        .text(
          cqseoData.i18n.freeRemaining
            .replace('%1$d', remaining)
            .replace('%2$d', freeLimit)
        )
        .show();
    } else {
      $remaining.hide();
    }

    $('#cqseo-results').show();
  }

  /**
   * エラーを表示
   */
  function showError(message) {
    $('#cqseo-error').find('p').text(message).end().show();
  }

  // イベントバインド
  $(function () {
    $('#cqseo-run-check').on('click', runCheck);

    $('#cqseo-url').on('keypress', function (e) {
      if (e.which === 13) {
        e.preventDefault();
        runCheck();
      }
    });
  });
})(jQuery);
