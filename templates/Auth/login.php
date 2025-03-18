<?php

/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */


$this->setLayout('auth');
$this->assign('title', "Login");

?>

<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
    <a href="<?= $this->Url->build('/') ?>" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
      <img class="w-10 h-10 mr-2" src="<?= $this->Url->image('fav-cloudpe-logo.svg'); ?>" alt="logo">
      CloudPe
    </a>
    <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
      <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
          Sign in to your account
        </h1>
        <?= $this->Form->create(null, ['type' => 'post', "class" => "space-y-4 md:space-y-6"]) ?>
        <div>
          <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
          <input type="email" name="login" id="login" class="input-text" placeholder="name@company.com" required="" autocomplete="email" value="<?= $this->request->getData('login') ?>">
        </div>
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
          <input type="password" name="password" id="password" class="input-text" placeholder="••••••••" autocomplete="current-password" value="<?= $this->request->getData('password') ?>">
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-start">
            <!-- <div class="flex items-center h-5">
              <input id="remember" aria-describedby="remember" type="checkbox" class="input-checkbox">
            </div>
            <div class="ml-3 text-sm">
              <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
            </div> -->
          </div>
          <!-- <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a> -->
        </div>
        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign in</button>
        <!-- <p class="text-sm font-light text-gray-500 dark:text-gray-400">
          Don’t have an account yet? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</a>
        </p> -->
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</section>
