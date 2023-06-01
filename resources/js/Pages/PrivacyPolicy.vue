<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>
<script>
import AppLayout from "../Layouts/AppLayout.vue";

export default {
    layout: AppLayout,
};
</script>
<template>
    <Head title="Privacy Policy" />
    <div class="break-words px-2 pt-12 md:px-10">
        <h1 class="mt-6 mb-12 text-3xl font-bold">PRIVACY POLICY</h1>
        <p>
            Unofficial Patra
            DB（以下、「当サイト」とします。）及びその管理を行う個人（以下、「管理犬」とします。）は、当サイトの訪問者（以下、「訪問者」とします。）の個人情報もしくはそれに準ずる情報の保護について、以下のとおりプライバシーポリシー（以下「本ポリシー」とします。）を定め、これに従って適法かつ適正に取り扱います。
        </p>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">1. 基本方針</h2>
            <p class="text-xs md:text-sm">
                当サイトは、個人情報の重要性を認識し、個人情報を保護することが社会的責務であると考え、個人情報に関する法令を遵守し、当サイトで取扱う個人情報の取得、利用、管理を適正に行います。当サイトのサービスを運営するにあたり必要な範囲で個人情報を取得し、利用目的の範囲内で適切に取り扱います。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">2. 適用範囲</h2>
            <p class="text-xs md:text-sm">
                本プライバシーポリシーは、当サイトにおいてのみ適用されます。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">3. 情報の取得と利用</h2>
            <p class="text-xs md:text-sm">
                取得する訪問者に関する情報と利用目的、保存期間等は下記の通りです。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base" id="infoList">3-1. 取り扱う情報</h2>
            <p class="mb-2 text-xs md:text-sm">
                取得・利用・管理する情報は以下のとおりです。
            </p>
            <ul class="list-inside list-disc pl-4 text-xs md:text-sm">
                <li>ハンドルネーム</li>
                <li>パスワード</li>
                <li>当サイト内ページの閲覧履歴</li>
                <li>ユーザーエージェント文字列</li>
                <li>Cookie</li>
                <li>IPアドレス</li>
            </ul>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">3-2. 情報の利用目的</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトにおける<a class="link-primary link" href="#infoList"
                    >3-1.</a
                >に記載した情報の利用目的は以下の通りです。
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base">3-2-1. ハンドルネーム</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは、Registerページでユーザー登録を行った訪問者（以下、「ログインユーザー」とします。）が設定したハンドルネームを取得・保管し、訪問者の識別に使用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ハンドルネームは平文として保管され、管理犬が閲覧することができます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                また、データ登録時にログインユーザーが許可した場合、他の訪問者はハンドルネームを閲覧することができます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ログインユーザーが許可しない場合、ハンドルネームを管理犬以外に公開することはありません。
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base">3-2-2. パスワード</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは、ログインユーザーが設定したパスワードを取得・保管し、訪問者の識別に使用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                パスワードは匿名化（ハッシュ化）された文字列として保管されます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                匿名化されたパスワードは管理犬が閲覧することができますが、管理犬がパスワードを特定することはできません。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                パスワードを管理犬以外の団体または個人に公開することはありません。
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base">3-2-3. 当サイト内ページの閲覧履歴</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは、訪問者の閲覧したページを取得・保管し、サービスの質を向上させるために解析し利用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                閲覧履歴には、以下の情報が含まれます
            </p>
            <ul class="list-inside list-disc pl-4 text-xs md:text-sm">
                <li>閲覧した時刻</li>
                <li>閲覧したページ</li>
                <li>IPアドレス</li>
                <li>閲覧ユーザー（ログイン時のみ）</li>
            </ul>
            <p class="mb-2 text-xs md:text-sm">
                なお、当サイトに属さないウェブページに関しては取得できません。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                閲覧履歴は管理犬が自由に閲覧することができます。管理犬以外の団体または個人に公開することはありません。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                IPアドレスの取得・利用及び管理については<a
                    href="#ipAddress"
                    class="link-primary link"
                    >3-2-6.</a
                >を参照してください。
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base">3-2-4. ユーザーエージェント文字列</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは、訪問者のユーザーエージェント文字列を取得・保管し、サービスの質を向上させるために解析し利用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ユーザーエージェント文字列とは、訪問者が使用しているPC端末またはスマホ端末の種類・ブラウザの種類およびバージョン・表示エンジン・コンポーネント情報等を含む文字列のことを指します。これら使用して個人を特定することはできません。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ユーザーエージェント文字列は当サイトを訪問した際、自動的にサーバーに送信され、管理犬が自由に閲覧することができます。管理犬以外の団体または個人に公開することはありません。
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base">3-2-5. Cookie</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは、訪問者の使用しているブラウザに対し、Cookieを送信します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                Cookie（クッキー）とは、ウェブサイトを利用したときに、ブラウザとサーバーとの間で送受信した利用履歴や入力内容などを、訪問者のコンピュータにファイルとして保存しておく仕組みです。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                Cookieは、以下を目的として発行・使用されます。
            </p>
            <ul class="list-inside list-disc pl-4 text-xs md:text-sm">
                <li>ログイン状態の保持</li>
                <li>フォーム入力情報の保持</li>
            </ul>
            <p class="mb-2 text-xs md:text-sm">
                当サイトはアナリティクスを使用しておらず、当サイトが発行したCookieを第三者が取得することはありません。Cookieは操作されない場合、1年間保存されます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは埋め込みコンテンツ（YouTube、Twitterコンテンツ等）を含みます。他サイトからの埋め込みコンテンツは、訪問者がそのサイトを閲覧した場合と同じように振る舞い、データの収集、Cookie
                の使用、サードパーティによる追加トラッキングの埋め込み、埋め込みコンテンツとのやりとりの監視を行うことがあります。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                埋め込まれた他サイトが発行・取得するCookieは、当該第三者のプライバシーポリシーに従って取り扱われます
            </p>
        </div>
        <div class="my-4 pl-8">
            <h2 class="mb-2 text-base" id="ipAddress">3-2-6. IPアドレス</h2>
            <p class="mb-2 text-xs md:text-sm">
                訪問者がサイトを訪問した際またはログイン・ログアウトした際および投稿時、IPアドレスを自動的に取得し保管します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                IPアドレスは管理犬が閲覧することができます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                IPアドレスを使用して個人を特定するにはプロバイダに対し正当な理由のもと開示を求める必要があるため、管理犬がIPアドレスを使用して個人を特定することはありません。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ただし、以下の第三者からのプロバイダ責任制限法に基づき、発信者情報開示請求・開示命令を受けた場合、管理犬は当該第三者に対し、当該第三者が請求した範囲に限りIPアドレスを開示します。その際、第三者がプロバイダにIPアドレスを元に開示請求を行い、個人を特定する場合があります。
            </p>
            <ul class="mb-2 list-inside list-disc pl-4 text-xs md:text-sm">
                <li>警察機関</li>
                <li>774inc.</li>
                <li>ななしいんく所属タレント（周防パトラさんを含む）</li>
            </ul>
            <p class="mb-2 text-xs md:text-sm">
                取得されたIPアドレスは1年間保管されます。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                ただし、プロバイダ責任制限法に基づき、開示請求・開示命令の対象となった投稿を受信した際に取得したIPアドレスの他に、必要な場合に当該投稿を行ったログインユーザーがログインまたはログアウトした際に取得したIPアドレスも開示することがあります。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">4. 情報の管理</h2>
            <p class="text-xs md:text-sm">
                当サイトは、訪問者からご提供いただいた情報の管理について、以下を徹底します。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">4-1. 情報の正確性の確保</h2>
            <p class="mb-2 text-xs md:text-sm">
                訪問者からご提供いただいた情報については、常に正確かつ最新の情報となるよう努めます。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">4-2. 安全管理措置</h2>
            <p class="mb-2 text-xs md:text-sm">
                個人情報の漏えいや滅失又は棄損を防止するために、適切なセキリュティ対策を実施して個人情報を保護します。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">4-3. 個人情報の廃棄</h2>
            <p class="mb-2 text-xs md:text-sm">
                個人情報が不要となった場合には、すみやかに廃棄します。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">
                4-4. 個人情報の開示、訂正、追加、削除、利用停止
            </h2>
            <p class="mb-2 text-xs md:text-sm">
                訪問者ご本人からの個人情報の開示、訂正、追加、削除、利用停止のご希望の場合には、ご本人であることを確認させていただいた上、速やかに対応させていただきます。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">
                5. サービスの質向上を目的とする情報利用
            </h2>
            <p class="text-xs md:text-sm">
                当サイトはサービスの質を向上させるために、訪問者から取得した情報およびログインユーザーが投稿したコンテンツを分析し、利用します。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">5-1. AI学習</h2>
            <p class="mb-2 text-xs md:text-sm">
                当サイトは感情分析AIを搭載しています。詳しくは<Link
                    class="link-primary link"
                    :href="route('sitepolicy')"
                    >SitePolicy</Link
                >を参照してください。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                当該AIの最適化を目的とし、ログインユーザーの意思によって投稿されたコンテンツの内容を利用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                取得した情報およびそれを元に構築した学習データは当該AIに対してのみ使用し、また当該AIは当サイトのサービスにおいてのみ使用されます。第三者に提供することはありません。
            </p>
        </div>
        <div class="my-4 pl-4">
            <h2 class="mb-2 text-base">
                5-2. その他、サービスを提供するシステムの最適化
            </h2>
            <p class="mb-2 text-xs md:text-sm">
                訪問者から取得した情報を解析し、サービスの質を向上に利用します。
            </p>
            <p class="mb-2 text-xs md:text-sm">
                取得した情報およびそれを解析して得た情報は当サイトに対してのみ使用し、第三者に公開することはありません。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">6. 未成年の個人情報について</h2>
            <p class="text-xs md:text-sm">
                未成年者が当サイトに投稿をしたり、お問い合わせフォームから問い合わせをされたりする場合は必ず親権者の同意を得るものとし、コメントやお問い合わせをされた時点で、当プライバシーポリシーに対して親権者の同意があるものとみなします。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">
                7. プライバシーポリシーの変更について
            </h2>
            <p class="text-xs md:text-sm">
                個人情報に関して適用される日本の法令及び<a
                    class="link-primary link"
                    href="https://www.774.ai/guideline"
                    target="_blank"
                    rel="noreferrer noopener"
                    >774inc.が制定する「774inc.」所属タレント二次創作ガイドライン</a
                >を遵守するとともに、本プライバシーポリシーの内容を適宜見直しその改善に努めます。修正された最新のプライバシーポリシーは常に本ページにて開示されます。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 md:text-lg">8. 問い合わせ先</h2>
            <p class="text-xs md:text-sm">
                <a
                    href="https://twitter.com/Mercury_ptrdog"
                    class="link-primary link"
                    target="_blank"
                    rel="noreferrer noopener"
                    >管理犬のTwitter（@Mercury_ptrdog）</a
                >のDMまたはリプライ、もしくはGoogleフォームにてお問い合わせください。
            </p>
            <p class="text-xs md:text-sm">
                当プライバシーポリシー及び<Link
                    class="link-primary link"
                    :href="route('sitepolicy')"
                    >Site Policy</Link
                >に基づき、適切かつ迅速な対応に努めます。
            </p>
        </div>
        <div class="my-8">
            <p class="text-xs md:text-sm">2023年5月6日　制定</p>
        </div>
        <div class="my-12">
            <h2 class="md:text-lg">
                当サイトのサービスおよび管理犬が訪問者が行った投稿や操作に対し編集・削除を行うことがあります。詳しくは<Link
                    class="link-primary link"
                    :href="route('sitepolicy')"
                    >Site Policy</Link
                >を参照してください。
            </h2>
        </div>
    </div>
</template>
