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
    <Head title="Site Policy" />
    <div class="break-words px-2 pt-12 text-sm md:px-10 md:text-base">
        <h1 class="mt-6 mb-12 text-3xl font-bold">SITE POLICY</h1>
        <h2 class="my-2 text-base md:text-lg">
            個人情報もしくはそれに準ずる情報の保護については、<Link
                class="link-primary link"
                :href="route('privacypolicy')"
                >PrivacyPilicy</Link
            >を参照してください。
        </h2>
        <p>
            Unofficial Patra
            DB（以下、「当サイト」とします。）を訪問される方（以下、「訪問者」とします。）は、以下の内容（以下、「本規約」とします。）を十分にお読みください。
        </p>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">1. 適用</h2>
            <p class="text-xs md:text-sm">
                本規約は，ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。
            </p>
            <p class="text-xs md:text-sm">
                本規約のほか，ご利用にあたってのルール等，各種の定め（以下，「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず，本規約の一部を構成するものとします。
            </p>
            <p class="text-xs md:text-sm">
                本規約の規定が前条の個別規定の規定と矛盾する場合には，個別規定において特段の定めなき限り，個別規定の規定が優先されるものとします。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">2. 基本方針</h2>
            <p>
                当サイトの管理を行う個人（以下、「管理犬」とします。）は、以下の項目に対し、細心の注意を払い、当サイトを運用またはシステムの構成をいたします。
            </p>
            <ul class="my-4 list-inside list-disc pl-4">
                <li>
                    当サイトに属するコンテンツ内における、周防パトラさん（以下、管理犬の謎のこだわりにより「パトラ姉さん」と呼称します。）を含むななしいんく所属タレントに関する不利益の一切の排除
                </li>
                <li>
                    <a
                        class="link-primary link"
                        href="https://www.774.ai/guideline"
                        target="_blank"
                        rel="noreferrer noopener"
                        >774inc.が制定する「774inc.」所属タレント二次創作ガイドライン</a
                    >の遵守
                </li>
            </ul>
            <p>
                また、管理犬の対応を必要とする場合、24時間以内に対応することのできる環境を維持した上での本規約に基づいた当サイトの運用を行います。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">3. コンテンツの管理</h2>
            <p class="text-xs md:text-sm">
                当サイトのシステム及び管理犬は24時間以内に、当サイトに掲載された情報の変更および削除を行える状態を維持いたします。
            </p>
            <p class="text-xs md:text-sm">
                当サイトのシステム及び管理犬は予告なく掲載されている情報を変更することがあります。
            </p>
            <p class="text-xs md:text-sm">
                また、当サイトのシステム及び管理犬は、以下の内容を含む訪問者が投稿した情報に対し、編集・非表示処理または削除を行うことがあります。
            </p>
            <ul class="my-4 list-inside list-disc pl-4">
                <li>古い情報（スケジュールの時刻及び内容）</li>
                <li>事実と異なった情報（スケジュールの時刻及び内容）</li>
                <li>特定の自然人または法人を誹謗し、中傷するもの</li>
                <li>極度にわいせつな内容を含むもの</li>
                <li>
                    禁制品の取引に関するものや、他者を害する行為の依頼など、法律によって禁止されている物品、行為の依頼や斡旋などに関するもの
                </li>
                <li>
                    その他、公序良俗に反し、または管理人によって承認すべきでないと認められるもの
                </li>
            </ul>
            <div
                class="my-2 rounded-lg bg-ptr-dark-brown p-5 leading-loose text-white"
            >
                <p class="my-1 text-xs md:text-sm">
                    なお、当サイトは、「当サイトに属するコンテンツ内における、パトラ姉さんを含むななしいんく所属タレントに関する不利益の一切の排除」を目的として、<span
                        class="text-base text-accent md:text-lg"
                        >訪問者が投稿できるコンテンツに対し感情分析AIを用いてネガティブ指数を測定します。</span
                    >
                </p>
                <p class="my-1 text-base text-accent md:text-lg">
                    ネガティブ高い投稿は自動的にシステムによって非表示処理が行われ、管理犬が手動で表示処理を行うまで当サイト上に表示されません。
                </p>
                <p class="my-1 text-xs md:text-sm">
                    感情分析AIの精度によって、ネガティブな要素を含まないもしくは文脈上ネガティブな要素となり得ない場合でも非表示処理が行われる場合があります（その場合、投稿完了前に警告表示がされます。）が、その場合必ず24時間以内に管理犬が確認し、処理を行います。
                </p>
                <p class="my-1 text-xs md:text-sm">
                    当サイトが搭載している感情分析AIはカスタムの機械学習モデルを構築した上で運用しています。訪問者の利用傾向やパトラ姉さんのコンテンツ傾向により随時最適化を行います。
                </p>
                <p class="my-1 text-xs md:text-sm">
                    AI学習に使用される情報に関しては<Link
                        class="link-primary link"
                        :href="route('privacypolicy')"
                        >PrivacyPolicy</Link
                    >を参照してください。
                </p>
                <p class="my-1 text-xs md:text-sm">
                    また、<span class="text-base text-accent md:text-lg"
                        >誹謗中傷を含むものは削除処理を行い、当該投稿を行った訪問者が保持するデバイストークンを用いたサービス利用及びログインする必要のあるサービスの利用拒否処理を行います。</span
                    >
                </p>
                <p class="my-1 text-xs md:text-sm">
                    イレギュラーは排除します。（管理AI打倒しようとしないでくださいね、鯖費がかかるんで…。）
                </p>
                <p class="my-6 text-base text-accent md:text-lg">
                    当サイトをご利用いただく際は、コンテンツの管理に伴い、管理犬個人（個犬…？）の思想及び裁量による編集・削除等の処理が行われることを予めご了承下さい。
                </p>
            </div>
        </div>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">4. 免責事項</h2>
            <p class="text-xs md:text-sm">
                当サイトは、細心の注意を払って情報を掲載しておりますが、この情報の正確性および完全性を保証するものではありません。
            </p>
            <p class="text-xs md:text-sm">
                訪問者が当サイトに含まれる情報もしくは内容をご利用されたことで直接・間接的に生じた損失に関し一切責任を負うものではありません。
            </p>
            <p class="text-xs md:text-sm">
                当サイトからリンクやバナーなどによって他のサイトに移動された場合、移動先サイトで提供される情報、サービス等について一切の責任を負いません。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">5. パスワードの管理</h2>
            <p class="text-xs md:text-sm">
                当サイトにRegisterページにてユーザー登録をした訪問者（以下、「ログインユーザー」とします）は、自己の責任において、本サイトに登録したハンドルネームおよびパスワードを適切に管理するものとします。
            </p>
            <p class="text-xs md:text-sm">
                ログインユーザーが、ハンドルネーム及びパスワードの組み合わせを第三者に譲渡または貸与し、第三者がログインした場合には、そのハンドルネームを登録しているログインユーザー自身による利用とみなします。
            </p>
            <p class="text-xs md:text-sm">
                ハンドルネーム及びパスワードが第三者によって使用されたことによって生じた損害は、当サイト及び管理犬に故意または重大な過失がある場合を除き、一切の責任を追わないものとします。
            </p>
        </div>
        <div class="my-8">
            <h2 class="mb-2 text-base md:text-lg">6. 著作権・肖像権等</h2>
            <p class="text-xs md:text-sm">
                当サイトに掲載されている文章、イラスト、ロゴ、写真、動画、ソフトウエア、その他のすべての情報の著作権・肖像権等は各権利所有者に帰属します。
            </p>
            <p class="text-xs md:text-sm">
                第三者に帰属する情報に関して、権利を侵害する目的はありません。各権利所有者様本人が<a
                    href="https://twitter.com/Mercury_ptrdog"
                    class="link-primary link"
                    target="_blank"
                    rel="noreferrer noopener"
                    >管理犬のTwitter（@Mercury_ptrdog）</a
                >のDMにてお問い合わせください。
            </p>
            <p class="text-xs md:text-sm">
                確認後、適切かつ迅速な対応に努めます。
            </p>
        </div>
    </div>
</template>
