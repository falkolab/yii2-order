<?php
use yii\bootstrap\ActiveForm;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use pistol88\order\widgets\ChooseClient;

 ?>
<div class="form-light-container">
    <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
            'action' => Url::to(['/order/order/create-ajax']),
            'options' => [
                'data-role' => 'order-form',
                'data-ajax' => $useAjax ? 'true' : 'false',
            ]
        ]);
    ?>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a class="heading collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseTwo">
                        Клиент
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false">
                <div class="panel-body">
                    <?= ChooseClient::widget(['form' => $form, 'model' => $model]);?>
                    <select class="form-control service-choose-property">
                        <option>Автомобиль...</option>
                    </select>
                </div>
            </div>
        </div>

        <?php if ($staffer) { ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="staffer-heading">
                    <h4 class="panel-title">
                        <a class="heading collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#staffer-collapse" aria-expanded="false" aria-controls="staffer-collapse">
                            Работник
                        </a>
                    </h4>
                </div>
                <div id="staffer-collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="staffer-collapse" aria-expanded="false">
                    <div class="panel-body">
                        <?php foreach ($staffer as $key => $worker) { ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="<?= $worker->id ?>" checked name='Order[staffer][]' ><?= $worker->name ?> (<?= $worker->category->name ?>)
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="heading" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Заказ
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false">
                <div class="row panel-body">
                    <div class="col-lg-12">
                        <div style="display: none;">
                            <?= $form->field($model, 'status')->label(false)->textInput(['value' => 'new', 'type' => 'hidden', 'maxlength' => true]) ?>
                            <?php if(yii::$app->has('organization') && $organization = yii::$app->organization->get()) { ?>
                                <?= $form->field($model, 'organization_id')->label(false)->textInput(['value' => $organization->id]) ?>
                            <?php } ?>
                        </div>
                        <?= $form->field($model, 'payment_type_id')->dropDownList($paymentTypes) ?>
                    </div>
                    <div class="col-lg-12 col-xs-12">
                        <?= $form->field($model, 'comment')->textArea(['maxlength' => true]) ?>
                    </div>
					<?php if($fields = $model->allfields) { ?>
						<div class="col-lg-12 col-xs-12">
							<?php foreach($fields as $fieldModel) { ?>
								<div class="col-lg-12 col-xs-12">
									<?php
									if($widget = $fieldModel->type->widget) {
										echo $widget::widget(['form' => $form, 'fieldModel' => $fieldModel]);
									}
									else {
										echo $form->field(new FieldValue, 'value['.$fieldModel->id.']')->label($fieldModel->name)->textInput(['required' => ($fieldModel->required == 'yes')]);
									}
									?>
									<?php if($fieldModel->description) { ?>
										<p><small><?=$fieldModel->description;?></small></p>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group offer">
            <?= Html::button(Yii::t('order', 'Create order'), ['class' => 'btn btn-success', 'id' => 'order-form-light-submit']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
