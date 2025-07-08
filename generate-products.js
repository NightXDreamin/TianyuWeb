// generate-products.js

const fs = require('fs-extra');
const path = require('path');
const fm = require('front-matter');
const marked = require('marked');

const sourceDir = path.join(__dirname, 'src_products');
const outputDir = path.join(__dirname, 'web', 'product_lib');
const templatePath = path.join(__dirname, 'product-template.html');

async function generate() {
    console.log('🚀 开始自动化生产产品页面...');

    // 读取HTML模板
    const template = await fs.readFile(templatePath, 'utf-8');
    const categories = await fs.readdir(sourceDir);

    for (const category of categories) {
        const categoryPath = path.join(sourceDir, category);
        const categoryStat = await fs.stat(categoryPath);

        if (categoryStat.isDirectory()) {
            const productFiles = await fs.readdir(categoryPath);
            const categoryData = []; // 用于存放该分类所有产品的信息

            for (const file of productFiles) {
                if (path.extname(file) === '.md') {
                    const mdPath = path.join(categoryPath, file);
                    const content = await fs.readFile(mdPath, 'utf-8');
                    const { attributes, body } = fm(content);
                    const htmlContent = marked.parse(body);

                    // 1. 生成产品详情页HTML
                    let productHtml = template;
                    productHtml = productHtml.replace('{{SEO_TITLE}}', attributes.seo.title);
                    productHtml = productHtml.replace('{{SEO_DESCRIPTION}}', attributes.seo.description);
                    productHtml = productHtml.replace('{{PRODUCT_NAME}}', attributes.productName);
                    
                    const imageHtml = attributes.images.map(img => `<img src="${img}" alt="${attributes.productName}">`).join('');
                    productHtml = productHtml.replace('{{IMAGE_GALLERY}}', imageHtml);
                    
                    productHtml = productHtml.replace('{{PRODUCT_CONTENT}}', htmlContent);

                    // 准备输出路径
                    const outputCategoryDir = path.join(outputDir, category);
                    await fs.ensureDir(outputCategoryDir);
                    const outputFilePath = path.join(outputCategoryDir, file.replace('.md', '.html'));

                    await fs.writeFile(outputFilePath, productHtml);
                    console.log(`✅ 已生成: ${outputFilePath}`);

                    // 2. 准备分类数据清单
                    categoryData.push({
                        name: attributes.productName,
                        thumbnail: attributes.thumbnail,
                        link: `/product_lib/${category}/${file.replace('.md', '.html')}`
                    });
                }
            }

            // 3. 生成该分类的JSON数据清单
            const jsonOutputPath = path.join(outputDir, category, 'products.json');
            await fs.writeJson(jsonOutputPath, categoryData, { spaces: 2 });
            console.log(`📦 已生成数据清单: ${jsonOutputPath}`);
        }
    }
    console.log('✨ 自动化生产完成！');
}

generate().catch(err => console.error(err));