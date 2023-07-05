<?php
header("Content-Type:text/css");
$font_family = $_GET['font_family']; // Change your Color Here
$fill_theme = $_GET['fill_theme'];
?>

/*-- Google Font --*/
@import url("https://fonts.googleapis.com/css2?family=<?php echo $font_family; ?>:wght@300;400;500;600;700;800;900&display=swap");

body {
    line-height: 1.7;
    font-size: var(--main-text-size);
    font-style: normal;
    font-weight: 400;
    color: var(--main-text-color);
    font-family: <?php echo $font_family; ?>, sans-serif;
    word-spacing: 0.05rem;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  color: var(--main-title-color);
  font-weight: 600;
  margin-top: 0;
  font-family: <?php echo $font_family; ?>, sans-serif;
}

h1 {
  font-size: 36px;
}

h2 {
  font-size: 32px;
}

h3 {
  font-size: 26px;
}

h4 {
  font-size: 20px;
}

h5 {
  font-size: 16px;
}

h6 {
  font-size: 14px;
}

p {
  font-size: var(--main-text-size);
  font-weight: 400;
  line-height: 1.7;
  color: var(--main-text-color);
  margin-bottom: 5px;
  font-family: <?php echo $font_family; ?>, sans-serif;
  word-spacing: 0.05rem;
}
span {
  font-size: var(--main-text-size);
  font-weight: 400;
  line-height: 1.7;
  color: var(--main-text-color);
  font-family: <?php echo $font_family; ?>, sans-serif;
  word-spacing: 0.05rem;
}
a {
  font-size: var(--main-text-size);
  color: var(--main-title-color);
  font-family: <?php echo $font_family; ?>, sans-serif;
}
a:hover {
  color: var(--main-theme-color);
}
.section-title {
  margin-bottom: 20px;
}
.section-title h1 {
  padding: <?php echo $fill_theme=='enable' ? '10px 20px' : '' ?>;
  border-radius: 5px;
  background: var(--main-title-fill-bg);
  color: var(--main-title-fill-color);
  font-size: var(--main-title-size);
  margin-bottom: 10px;
  display: inline-block;
}
.section-title span {
  font-size: var(--main-subtitle-size);
  font-weight: 500;
  display: block;
}
.section-title.hero-size h1 {
  font-size: var(--main-hero-title-size);
}
.section-title.hero-size span {
  font-size: var(--main-hero-subtitle-size);
}
.hero-slider-content-1 h1.font-dec {
  font-size: 39px;
  line-height: 48px;
  font-family: <?php echo $font_family; ?>, sans-serif;
  margin: 16px 0 28px;
}
.main-menu > nav > ul > li a{
  font-size: var(--main-menu-sỉze);
  font-weight: 700;
  font-family: <?php echo $font_family; ?>, sans-serif;
  color: var(--main-title-color);
}
.category-menu nav > ul > li > a{
  font-family: <?php echo $font_family; ?>, sans-serif;
  color: var(--main-title-color);
}
@media only screen and (max-width: 480px) {
  .section-title {
    margin-bottom: 15px;
  }
  .section-title h1 {
    font-size: 22px;
  }
  .section-title span {
    font-size: 18px;
  }
}
.heading span{
  background: var(--main-title-fill-bg);
  color: var(--main-title-fill-color);
  font-size: var(--main-title-size);
  display: inline-block;
  font-weight: 600;
  text-transform: capitalize;
}
.marquee marquee{
  font-size: var(--main-subtitle-size);
  font-weight: 500;
  display: block;
}
.trending-blog .content{
  border-bottom: 1px solid var(--main-title-fill-bg);
}
.trending-blog .content .left-content .heading{
  background: var(--main-title-fill-bg);
}
.trending-blog .content .right-content{
  background: <?php echo $fill_theme=='enable' ? "var(--main-title-fill-bg)" : "#ffffff" ?>;
}
.trending-blog .content .right-content span{
  color: var(--main-title-fill-color);
  font-size: var(--main-title-size);
  font-weight: 500;
}