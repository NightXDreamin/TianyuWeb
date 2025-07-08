// generate-products.js (V7 - Global Replace Fix)

const fs = require('fs-extra');
const path = require('path');
const fm = require('front-matter');
const marked = require('marked');

const sourceDir = path.join(__dirname, 'src_products');
const outputDir = path.join(__dirname, 'web', 'product_lib');
const templatePath = path.join(__dirname, 'product-template.html');

async function generate() {
    console.log('🚀 开始自动化生产产品页面 (V7 - 全局替换修正版)...');

    const template = await fs.readFile(templatePath, 'utf-8');
    const categories = await fs.readdir(sourceDir);

    for (const category of categories) {
        const categoryPath = path.join(sourceDir, category);
        const categoryStat = await fs.stat(categoryPath);

        if (categoryStat.isDirectory()) {
            const outputCategoryDir = path.join(outputDir, category);
            await fs.ensureDir(outputCategoryDir);
            
            const productFiles = await fs.readdir(categoryPath);
            const categoryData = []; 

            for (const file of productFiles) {
                if (path.extname(file) === '.md') {
                    const mdPath = path.join(categoryPath, file);
                    const content = await fs.readFile(mdPath, 'utf-8');
                    const { attributes, body } = fm(content);
                    const htmlContent = marked.parse(body);

                    let productHtml = template;
                    
                    // --- 关键改动：所有 .replace() 都改用正则表达式 /.../g 来实现全局替换 ---
                    productHtml = productHtml.replace(/{{SEO_TITLE}}/g, attributes.seo.title || '产品详情');
                    productHtml = productHtml.replace(/{{SEO_DESCRIPTION}}/g, attributes.seo.description || '');
                    productHtml = productHtml.replace(/{{PRODUCT_NAME}}/g, attributes.productName);
                    
                    const thumbnailListHtml = (attributes.images || [])
                        .map(img => `<li><img src="${img}" alt="${attributes.productName} 缩略图"></li>`)
                        .join('');
                    productHtml = productHtml.replace(/{{THUMBNAIL_LIST}}/g, thumbnailListHtml);

                    const firstImage = attributes.images && attributes.images.length > 0 ? attributes.images[0] : '/assets/img/placeholder.png';
                    productHtml = productHtml.replace(/{{FIRST_IMAGE}}/g, firstImage);

                    const shortDescriptionHtml = marked.parse(attributes.seo.shortDescription || '');
                    productHtml = productHtml.replace(/{{PRODUCT_SHORT_DESCRIPTION}}/g, shortDescriptionHtml);

                    productHtml = productHtml.replace(/{{PRODUCT_CONTENT}}/g, htmlContent);

                    const outputFilePath = path.join(outputCategoryDir, file.replace('.md', '.html'));
                    await fs.writeFile(outputFilePath, productHtml);
                    console.log(`✅ 已生成: ${outputFilePath}`);

                    const thumbnail = attributes.thumbnail || firstImage;
                    categoryData.push({
                        name: attributes.productName,
                        thumbnail: thumbnail,
                        link: `/product_lib/${category}/${file.replace('.md', '.html')}`
                    });
                }
            }

            const jsonOutputPath = path.join(outputCategoryDir, 'products.json');
            await fs.writeJson(jsonOutputPath, categoryData, { spaces: 2 });
            console.log(`📦 已生成数据清单: ${jsonOutputPath}`);
        }
    }
    console.log('✨ 自动化生产完成！');
}

generate().catch(err => console.error(err));