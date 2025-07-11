<?php
    // --- 招聘职位数据中心 ---
    // 以后所有职位的增删改，都在这个数组里完成！
    $jobs = [
        [
            "title" => "业务经理",
            "quota" => "5名",
            "salary" => "5000 - 10000",
            "requirements" => "50岁以下，积极向上、品行端正
专科及以上学历
有过业务推销相关经验，具有良好的团队带队能力
可独立进行业务拓展，提出相关建议"
        ],
        [
            "title" => "业务员",
            "quota" => "10名",
            "salary" => "3000 - 7000",
            "requirements" => 
                "品行端正、吃苦耐劳
对环保设备、环保工程等的销售有了解,
具有良好的团队协作能力"
        ],
        [
            "title" => "技术员 (招聘应届/可实习)",
            "quota" => "4名",
            "salary" => "3000 - 8000",
            "requirements" => 
                "本科及以上学历，环保专业优先
会制图，能进行现场勘探，精通CAD、PS等软件
具有良好的团队协作能力"
        ],
        [
            "title" => "污水站管理人员 (招聘应届/可实习)",
            "quota" => "10名",
            "salary" => "3000 - 5000",
            "requirements" => 
                "22岁以上，初中以上学历，经验不限
积极向上、吃苦耐劳、认真负责
公司提供上岗培训，包食宿，工作地点面议"
        ]
    ];
?>

<!DOCTYPE html>

<html lang="zh-CN">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <title>加入我们 | 诚聘英才 - 河南天昱环保</title>

    <meta name="description" content="河南天昱环保正在招聘业务经理、技术员、污水站管理员等多个职位，我们提供有竞争力的薪酬和广阔的发展平台，期待您的加入！">

    <link rel="stylesheet" href="/assets/css/main.css">

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

</head>

<body>



    <script src="/assets/js/header.js"></script>



    <main class="career-page">

        <section class="hero-about" style="background-image: url('/assets/img/1406前台.png');">

            <div class="container" data-aos="fade-in">

                <h1 class="hero-about__title">加入我们，成就梦想</h1>

                <p class="hero-about__subtitle">与天昱同行，共创环保事业的美好明天</p>

            </div>

        </section>



        <section class="philosophy-section">

            <div class="container" data-aos="fade-up">

                <p>我们深信，人才是公司最宝贵的财富。在这里，我们摒弃复杂的层级，营造轻松、开放、相互尊重的团队氛围。我们相信每一位伙伴的创造力，并致力于为大家提供一个能将个人价值与公司发展紧密结合的平台。如果你对环保事业怀有热忱，并渴望与一个充满活力的团队共同成长，我们期待你的加入。</p>

            </div>

        </section>



        <section class="jobs-section">

            <div class="container">

                <div class="section-title" data-aos="fade-up">

                    <h2>正在招聘的职位</h2>

                </div>

                

                <?php foreach ($jobs as $index => $job): ?>

                <div class="job-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">

                    <div class="job-card__header">

                        <h3 class="job-card__title"><?php echo htmlspecialchars($job['title']); ?></h3>

                        <span class="job-card__salary"><?php echo htmlspecialchars($job['salary']); ?></span>

                    </div>

                    <div class="job-card__body">

                        <div class="job-card__requirements">

                            <h4>招聘条件：</h4>

                            <ul>

                                <?php foreach ($job['requirements'] as $req): ?>

                                    <li><?php echo htmlspecialchars($req); ?></li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                        <div class="job-card__quota">

                            <h4>招聘名额：</h4>

                            <p><?php echo htmlspecialchars($job['quota']); ?></p>

                        </div>

                    </div>

                </div>

                <?php endforeach; ?>



            </div>

        </section>



        <section class="apply-section">

            <div class="container" data-aos="fade-up">

                <h2>心动不如行动</h2>

                <p>请将您的简历发送至我们的官方招聘邮箱</p>

                <a href="mailto:hr@tianyuhuanbao.com" class="apply-email">hr@tianyuhuanbao.com</a>

                <p style="margin-top: 20px;">或致电联系我们的招聘负责人</p>

                <a href="tel:15038078859" class="apply-phone">娄经理：15038078859</a>

            </div>

        </section>



    </main>



    <script src="/assets/js/footer.js"></script>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script src="/assets/js/main.js"></script>

</body>

</html>