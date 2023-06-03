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
            <h2 class="mb-2 text-base md:text-lg">0. はじめに</h2>
            <p class="text-sm md:text-base">
                当サイトは、周防パトラさん（以下、制作者の謎のこだわりにより「パトラ姉さん」と呼称します。）のファンが作成した、
            </p>
            <p class="my-2 text-sm md:text-base">
                <span class="text-xl text-ptr-main"
                    >非公式の二次創作コンテンツ</span
                >となります。
            </p>
            <p class="text-sm md:text-base">
                本規約に基づき、当サイトは運用されますが、非公式コンテンツであることを十分に理解した上でのご利用をお願いします。
            </p>
            <p class="text-sm md:text-base">
                非公式コンテンツであることにより、パトラ姉さんの意向に反するコンテンツが含まれる可能性があります。
            </p>
            <p class="text-sm md:text-base">
                当サイトの提供するコンテンツは主に、パトラ姉さんに関する情報の変化の把握及び過去コンテンツの閲覧に対し手軽さを提供することを目的としています。
            </p>
            <p class="text-sm md:text-base">
                正確な情報に関しては、
                <a
                    class="link-primary link"
                    href="https://ptrsuou.com/"
                    target="_blank"
                    rel="noreferrer noopener"
                    >「周防パトラオフィシャルサイト」</a
                >
                の閲覧及び
                <a
                    class="link-primary link"
                    href="https://twitter.com/Patra_HNST"
                    target="_blank"
                    rel="noreferrer noopener"
                    >Twitter</a
                >
                や
                <a
                    class="link-primary link"
                    href="https://www.youtube.com/channel/UCeLzT-7b2PBcunJplmWtoDg"
                    target="_blank"
                    rel="noreferrer noopener"
                    >YouTube</a
                >
                での情報閲覧を推奨します。
            </p>
            <div
                class="mt-4 flex flex-row flex-wrap items-center gap-y-2 gap-x-3"
            >
                <a
                    class="flex shrink-0 items-center gap-2 rounded-lg bg-ptr-main px-4 py-2 text-xs text-white md:text-sm"
                    href="https://ptrsuou.com/"
                    target="_blank"
                    rel="noreferrer noopener"
                >
                    <svg
                        class="aspect-square h-full fill-ptr-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 576 512"
                    >
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"
                        />
                    </svg>
                    周防パトラオフィシャルサイト</a
                >
                <a
                    class="flex shrink-0 items-center gap-2 rounded-lg bg-[#1DA1F2] px-4 py-2 text-xs text-white md:text-sm"
                    href="https://twitter.com/Patra_HNST"
                    target="_blank"
                    rel="noreferrer noopener"
                >
                    <svg
                        class="aspect-square h-full fill-ptr-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 512 512"
                    >
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
                        />
                    </svg>
                    Twitter</a
                >
                <a
                    class="flex shrink-0 items-center gap-2 rounded-lg bg-[#FF0000] px-4 py-2 text-xs text-white md:text-sm"
                    href="https://www.youtube.com/channel/UCeLzT-7b2PBcunJplmWtoDg"
                    target="_blank"
                    rel="noreferrer noopener"
                >
                    <svg
                        class="aspect-square h-full fill-ptr-white"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 576 512"
                    >
                        <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path
                            d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"
                        />
                    </svg>
                    YouTube</a
                >
            </div>
        </div>
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
        <div class="my-8 text-xs md:text-sm">
            <h2 class="mb-2 text-base md:text-lg">2. 基本方針</h2>
            <p>
                当サイトの管理を行う個人（以下、「管理犬」とします。）は、以下の項目に対し、細心の注意を払い、当サイトを運用またはシステムの構成をいたします。
            </p>
            <ul class="my-4 list-inside list-disc pl-4">
                <li>
                    当サイトに属するコンテンツ内における、パトラ姉さんを含む個人・法人に関する不利益の一切の排除
                </li>
                <li>
                    <a
                        class="link-primary link"
                        href="https://ptrsuou.com/?page_id=171"
                        target="_blank"
                        rel="noreferrer noopener"
                        >「周防パトラオフィシャルサイト」にて制定されているGUIDELINE</a
                    >の遵守
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
                    なお、当サイトは、「当サイトに属するコンテンツ内における、パトラ姉さんを含む個人・法人
                    に関する不利益の一切の排除」を目的として、<span
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
