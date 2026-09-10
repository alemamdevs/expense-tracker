@use('App\Icons\Ios')
@use('App\Icons\Android')

<native:pressable
    class="w-[56] h-[56] rounded-full bg-theme-primary items-center justify-center shadow-lg"
    @navigate="/transactions/add"
    press-scale="0.95"
    a11y-label="Add transaction"
>
    <native:icon :ios="Ios::Plus" :android="Android::Add" :size="28" class="text-theme-on-primary" />
</native:pressable>
