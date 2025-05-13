<style>
    .activity-box {
        width: 100%;
        padding: var(--spaceMD);
        margin-bottom: var(--spaceMD);
        border-radius: var(--roundedLG);
        position: relative;
        border: 2px solid #e0e0e0;
    }
    .activity-box .date-target {
        background: var(--primaryColor);
        width: fit-content;
        padding: var(--spaceXSM) var(--spaceSM);
        color: var(--whiteColor);
        font-weight: 500;
        letter-spacing: 0.5;
        border: none;
        font-size: var(--textSM);
        margin-bottom: var(--spaceXXSM);
        border-radius: var(--roundedLG);
    }
    .activity-box .activiy-desc{
        overflow: hidden; 
        text-overflow: ellipsis; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        line-clamp: 2; 
        -webkit-box-orient: vertical;
        font-size: var(--textMD);
    }
</style>
<?php 
    if(count($dt_my_activity['data']) > 0){
        echo "<div class='row'>";
        foreach($dt_my_activity['data'] as $dt){
            echo "
                <div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2'>
                    <div class='activity-box'>
                        <p class='mb-2 date-target'>$dt->created_at</p>
                        <h6 class='mb-1'>$dt->history_type</h6>
                        <p class='mb-0 text-secondary activiy-desc{'>$dt->history_context</p>
                    </div>
                </div>
            ";
        }
        echo "</div>";

        echo "<div class='d-inline-block'>
            <h5>Page</h5>";

        $active = 0;
        if($this->session->userdata('page_activity')){
            $active = $this->session->userdata('page_activity');
        }

        for($i = 0; $i < $dt_my_activity['total_page']; $i++){
            $page = $i + 1;
            echo "
                <form method='POST' class='d-inline' action='/HistoryController/navigate/$i'>
                    <button class='btn btn-page"; 
                    if($active == $i){echo " active";}
                    echo" me-1' type='submit'>$page</button>
                </form>
            ";
        }

        echo "</div>";
    } else {
        echo "
            <div class='text-center my-3'>
                <h5 class='text-secondary'>- You have no activity to show -</h5>
            </div>
        ";
    }
?>