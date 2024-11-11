@props(['currentViewType', 'studentRoute', 'companyRoute'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentViewType = '{{ $currentViewType }}';
    const toggleViewType = currentViewType === 'student' ? 'company' : 'student';
    const toggleRoute = currentViewType === 'student' ? '{{ $companyRoute }}' : '{{ $studentRoute }}';
});
</script>
